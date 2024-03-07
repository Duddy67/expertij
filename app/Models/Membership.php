<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use App\Traits\AccessLevel;
use App\Traits\CheckInCheckOut;
use App\Traits\OptionList;
use App\Traits\Renewal;
use App\Models\Cms\Setting;
use App\Models\User;
use App\Models\Cms\Document;
use App\Models\Cms\Payment;
use App\Models\Membership\Licence;
use App\Models\Membership\Language;
use App\Models\Membership\Jurisdiction;
use App\Models\Membership\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Membership extends Model
{
    use HasFactory, AccessLevel, CheckInCheckOut, OptionList, Renewal;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'memberships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'since',
        'professional_status',
        'professional_status_info',
        'siret_number',
        'naf_code',
        'linguistic_training',
        'extra_linguistic_training',
        'professional_experience',
        'observations',
        'why_expertij',
        'associated_member',
        'free_period',
        'member_list',
        'owned_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'checked_out_time',
        'member_since'
    ];

    const EARLIEST_YEAR = 1980;


    /**
     * Get the user that owns the membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The licences that belong to the membership.
     */
    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }

    /**
     * The votes that belong to the membership.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * The payments that belong to the membership.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * The professional attestation that belongs to the membership.
     */
    public function professionalAttestation(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')->where('field', 'professional_attestation');
    }

    /**
     * The resume that belongs to the membership.
     */
    public function resume(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')->where('field', 'resume');
    }

    /**
     * Delete the model from the database (override).
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete()
    {
        $this->licences()->delete();
        $this->votes()->delete();
        $this->payments()->delete();

        if ($this->professionalAttestation) {
            $this->professionalAttestation->delete();
        }

        parent::delete();
    }

    /*
     * Gets the membership items according to the filter, sort and pagination settings.
     */
    public static function getMemberships(Request $request)
    {
        $perPage = $request->input('per_page', Setting::getValue('pagination', 'per_page'));
        $search = $request->input('search', null);
        $statuses = $request->input('statuses', []);
        $memberType = $request->input('member_type', null);

        $query = Membership::query();
        $query->select('memberships.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
              ->leftJoin('users', 'memberships.user_id', '=', 'users.id');

        if ($search !== null) {
            $query->where('users.last_name', 'like', '%'.$search.'%');
        }

        // Filter by statuses
        if (!empty($statuses)) {
            $pendingOfflinePayment = false;

            // Check for the extra pending_offline_payment status.
            if (in_array('pending_offline_payment', $statuses)) {
                $pendingOfflinePayment = true;
                // Remove the extra status from the status filter array.
                $key = array_search('pending_offline_payment', $statuses);
                unset($statuses[$key]);
            }

            if (!empty($statuses)) {
                $query->whereIn('status', $statuses);
            }

            // Check for a pending payment.
            if ($pendingOfflinePayment) {
                $query->orWhereHas('payments', function($query) {
                    $query->where('status', 'pending')->where( function($query) {
                        $query->where('mode', 'cheque')->orWhere('mode', 'bank_transfer');
                    });
                });
            }
        }

        if ($memberType !== null) {
            $value = ($memberType == 'associated') ? 1 : 0;
            $query->where('associated_member', $value);
        }

        // Return all of the results or the paginated result according to the $perPage value.
        return ($perPage == -1) ? $query->paginate($query->count()) : $query->paginate($perPage);
    }

    public static function createExportList(Request $request): string
    {
        // Cancel pagination to get all of the results.
        $request->merge(['per_page' => -1]);
        // Run the membership query.
        $memberships = Membership::getMemberships($request);

        // Create the csv file from the query results.

        $list = [['Nom', 'Prénom', 'Email', 'Statut', 'Numéro adhérent']];
        $fields = [];

        foreach ($memberships as $membership) {
            $fields[] = $membership->user->first_name;
            $fields[] = $membership->user->last_name;
            $fields[] = $membership->user->email;
            $fields[] = __('labels.membership.'.$membership->status);
            $fields[] = ($membership->member_number) ? $membership->member_number : 'Pas encore adhérent';

            $list[] = $fields;
            $fields = [];
        }

        // Create a tmp directory in case it doesn't exists.
        Storage::makeDirectory('tmp');

        // Build the name of the file from the current date.
        $file = 'export-'.Carbon::now()->format('d-m-Y-H-i').'.csv';
        // Create the csv file.
        $fp = fopen(storage_path('app/tmp/'.$file), 'w');

        // Write into the file.
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        return storage_path('app/tmp/'.$file);
    }

    public static function getMembers(Request $request, bool $isRenewalPeriod, bool $associated)
    {
        $perPage = $request->input('per_page', Setting::getValue('pagination', 'per_page'));

        $query = Membership::query();
        $query->select('memberships.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
              ->leftJoin('users', 'memberships.user_id', '=', 'users.id');

        // TODO: Check the filter values and set the query accordingly.

        // Include the members with a pending renewal status during the renewal period.
        $whereIn = ($isRenewalPeriod) ? ['pending_renewal', 'member'] : ['member'];

        $query->where('associated_member', $associated)
              ->where('member_list', 1)
              ->whereIn('status', $whereIn);

        return $query->paginate($perPage);
    }

    public function getLicenceTypeOptions(): array
    {
        return [
            ['value' => 'expert',  'text' => __('labels.membership.expert')],
            ['value' => 'ceseda',  'text' => __('labels.membership.ceseda')],
        ];
    }

    public function getProfessionalStatusOptions(): array
    {
        return [
            ['value' => 'liberal_profession', 'text' => __('labels.membership.liberal_profession')],
            ['value' => 'micro_entrepreneur', 'text' => __('labels.membership.micro_entrepreneur')],
            ['value' => 'company', 'text' => __('labels.membership.company')],
            ['value' => 'other', 'text' => __('labels.generic.other')],
        ];
    }

    public function getSinceOptions(): array
    {
        $options = [];
        // Get the current year.
        $year = date('Y');

	while ($year >= self::EARLIEST_YEAR) {
            $options[] = ['value' => $year, 'text' => $year];
	    $year--;
	}

        return $options;
    }

    public function getCivilityOptions(): array
    {
        // Get the User relationship model then use its method.
        return $this->user()->getRelated()->getCivilityOptions();
    }

    public function getCitizenshipOptions(): array
    {
        // Get the User relationship model then use its method.
        return $this->user()->getRelated()->getCitizenshipOptions();
    }

    public function getLanguageOptions(): array
    {
        $options = [];
        $languages = Language::where('published', 1)->orderBy('fr')->get();

        foreach ($languages as $language) {
            $options[] = ['value' => $language->alpha_3, 'text' => $language->fr];
        }

        return $options;
    }

    public function getJurisdictionOptions(): array
    {
        $options = [];
        $jurisdictions = Jurisdiction::all();

        foreach ($jurisdictions as $jurisdiction) {
            // Create an array for each jurisdiction type.
            if (!isset($options[$jurisdiction->type])) {
                $options[$jurisdiction->type] = [];
            }

            $options[$jurisdiction->type][] = ['value' => $jurisdiction->id, 'text' => $jurisdiction->name];
        }

        return $options;
    }

    public function getStatusOptions(): array
    {
        return [
            ['value' => 'pending', 'text' => __('labels.membership.pending')],
            ['value' => 'refused', 'text' => __('labels.membership.refused')],
            ['value' => 'pending_subscription', 'text' => __('labels.membership.pending_subscription')],
            ['value' => 'cancelled', 'text' => __('labels.membership.cancelled')],
            ['value' => 'member', 'text' => __('labels.membership.member')],
            ['value' => 'pending_renewal', 'text' => __('labels.membership.pending_renewal')],
            ['value' => 'revoked', 'text' => __('labels.membership.revoked')],
            ['value' => 'cancellation', 'text' => __('labels.membership.cancellation')],
        ];
    }

    public function getMemberTypeOptions(): array
    {
        return [
            ['value' => 'normal',  'text' => __('labels.generic.normal')],
            ['value' => 'associated',  'text' => __('labels.membership.associated_member')],
        ];
    }

    /*
     * Checks if a given user has voted regarding a membership request.
     */
    public function hasUserVoted($user): bool
    {
        // Loop through the membership's votes.
        foreach ($this->votes as $vote) {
            if ($vote->user->id == $user->id) {
                return true;
            }
        }

        return false;
    }

    /*
     *  Checks whether the membership has a pending payment.
     */
    public function hasPendingPayment(): bool
    {
        return ($this->payments->where('status', 'pending')->first()) ? true : false;
    }

    public function getLastPayment(): ?Payment
    {
        return $this->payments->last();
    }

    /*
     * Creates and returns a payment that is set according to
     * the purchased item as well as the membership status.
     */
    public function createPayment(array $query): Payment
    {
        $item = '';
        $prices = Setting::getDataByGroup('prices', $this);
        $amount = 0;
        // Check the free period (if any).
        $freePeriod = ($query['payment_mode'] == 'free_period' && $this->free_period) ? true : false;

        // Set the amount value as well as the item type.

        if ($this->status == 'pending_subscription' || $this->status == 'pending_renewal') {
            $item = 'subscription';

            if (!$freePeriod) {
                $amount = ($this->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
            }

            // Check whether an insurance has been selected in addition to the subscription.
            $item = (isset($query['insurance_code']) && $query['insurance_code'] != 'f0') ? 'subscription_insurance_'.$query['insurance_code'] : $item;

            // Add the insurance price (if any).
            $amount = (str_starts_with($item, 'subscription-insurance-')) ? $amount + $prices['insurance_fee_'.$query['insurance_code']] : $amount;
        }
        elseif ($this->status == 'member' && isset($query['insurance_code']) && $query['insurance_code'] != 'f0') {
            $item = 'insurance_'.$query['insurance_code'];
            $amount = $prices['insurance_fee_'.$query['insurance_code']];
        }

        // Set the payment status according the payment mode and the free period value.
        $status = (($query['payment_mode'] == 'cheque' || $query['payment_mode'] == 'bank_transfer') && !$freePeriod) ? 'pending' : 'completed';

        $transactionId = ($query['payment_mode'] != 'cheque' && $query['payment_mode'] != 'bank_transfer' && !$freePeriod) ? $query['transaction_id'] : uniqid('OFFL');

        $payment = Payment::create([
            'item' => $item,
            'status' => $status,
            'amount' => $amount,
            'mode' => $query['payment_mode'],
            'currency' => 'EUR',
            'transaction_id' => $transactionId,
            'message' => (isset($query['message'])) ? $query['message'] : NULL,
            'data' => (isset($query['data'])) ? $query['data'] : NULL,
        ]);

        return $payment;
    }

    /*
     * Compute a new member number.
     */
    public function getMemberNumber(): string
    {
        // Get the highest member number.
        $lastNumber = Membership::max('member_number');
        // Initialise final letter as ceseda.
        $letter = 'C';

        // Check for associated member.
        if ($this->associated_member) {
            $letter = 'MA';
        }
        // then check for expert.
        else {
            foreach ($this->licences as $licence) {
                if ($licence->type == 'expert') {
                    $letter = 'E';
                    break;
                }
            }
        }

        if ($lastNumber === null) {
            return date('Y').'-1-'.$letter;
        }

        // Extract the highest member number.
        preg_match('#^[0-9]{4}-([0-9]*)-#', $lastNumber, $matches);
        // Increase the member number by one.
        $lastNumber = $matches[1];
        $newNumber = $lastNumber + 1;

        return date('Y').'-'.$newNumber.'-'.$letter;
    }

    /*
     *  Checks whether the membership has an insurance.
     */
    public function hasInsurance(): bool
    {
        return ($this->insurance_code) ? true : false;
    }

    public function getInsurance()
    {
        if ($this->insurance_code === null) {
            return null;
        }

        $prices = Setting::getDataByGroup('prices', $this);
        $insurance = new \stdClass();
        $insurance->name = __('labels.membership.insurance_'.$this->insurance_code);
        $insurance->price = $prices['insurance_fee_'.$this->insurance_code];
        $insurance->coverage = $prices['insurance_coverage_'.$this->insurance_code];

        return $insurance;
    }

    public function setInsurance($code)
    {
        $this->insurance_code = $code;
        $this->save();
    }

    public function cancelInsurance()
    {
        $this->insurance_code = null;
        $this->save();
    }

    public function createSubscriptionInvoice(Payment $payment)
    {
        $prices = Setting::getDataByGroup('prices', $this);
        $data = $this->getInvoiceData($payment);

        // Use the prices directly from the setting table to prevent computation in case the member has paid for both the subscription and the invoice.
        $data['subscription_fee'] = ($this->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
        $data['item_reference'] = __('labels.membership.subscription');

        $fileName = __('labels.membership.subscription_invoice_filename').'-'.$this->member_number.'-'.Carbon::today()->format('d-m-Y').'.pdf';
        $payment->createInvoice('pdf.membership.subscription-invoice', $data, $fileName);
    }

    public function createInsuranceInvoice(Payment $payment)
    {
        $prices = Setting::getDataByGroup('prices', $this);
        $data = $this->getInvoiceData($payment);

        // Get the insurance formula (f1, f2...).
        $formula = substr($payment->item, -2);
        $data['item_reference'] = __('labels.membership.insurance_'.$formula);
        // Use the prices directly from the setting table to prevent computation in case the member has paid for both the subscription and the invoice.
        $data['insurance_fee'] = $prices['insurance_fee_'.$formula];

        $fileName = __('labels.membership.insurance_invoice_filename').'-'.$this->member_number.'-'.Carbon::today()->format('d-m-Y').'.pdf';
        $payment->createInvoice('pdf.membership.insurance-invoice', $data, $fileName);
    }

    /*
     * Creates and returns the common data used in both subscription and insurance invoices. 
     */
    private function getInvoiceData(Payment $payment): array
    {
        $user = $this->user;

        $data = [];
        $data['civility'] = __('labels.user.'.$user->civility);
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['street'] = $user->address->street;
        $data['postcode'] = $user->address->postcode;
        $data['city'] = $user->address->city;
        $data['member_number'] = $this->member_number;

        $renewalDate = $this->getRenewalDate();

        if ($this->isRenewalPeriod()) {
            $renewalDate = $this->getLatestRenewalDate();
        }

        $data['subscription_year'] = $renewalDate->format('Y');
        $data['subscription_start_date'] = $renewalDate->format('d/m/Y');
        $renewalDate->addYear();
        $data['subscription_end_date'] = $renewalDate->format('d/m/Y');
        $data['current_date'] = Carbon::today()->format('d/m/Y');
        $data['payment_mode'] = $payment->payment_mode;

        return $data;
    }
}
