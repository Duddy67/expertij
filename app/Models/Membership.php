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
use App\Models\Cms\Setting;
use App\Models\User;
use App\Models\Cms\Document;
use App\Models\Cms\Payment;
use App\Models\Membership\Insurance;
use App\Models\Membership\Licence;
use App\Models\Membership\Language;
use App\Models\Membership\Jurisdiction;
use App\Models\Membership\Vote;

class Membership extends Model
{
    use HasFactory, AccessLevel, CheckInCheckOut, OptionList;

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
     * Get the insurance that owns the membership.
     */
    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
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
            $query->whereIn('status', $statuses);
        }

        if ($memberType !== null) {
            $value = ($memberType == 'associated') ? 1 : 0;
            $query->where('associated_member', $value);
        }

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
     *  Checks whether the membership has an insurance.
     */
    public function hasInsurance(): bool
    {
        //$payments = $this->payments->where('last', 1)->get();
        return ($this->payments->where('last', 1)->where('item', 'LIKE', '%insurance%')->first()) ? true : false;

        /*foreach ($payments as $payment) {
            if (str_starts_with($payment->item, 'insurance') || str_starts_with($payment->item, 'subscription-insurance')) {
                return true;
            }
        }

        return false;*/
    }

    /*
     *  Checks whether the membership has a pending payment.
     */
    public function hasPendingPayment(): bool
    {
        return ($this->payments->where('last', 1)->where('status', 'pending')->first()) ? true : false;
    }

    public function getCurrentPayment(): ?Payment
    {
        return $this->payments->where('last', 1)->first();
    }

    public function getPayment(array $query): Payment
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

            if ($this->status == 'pending_renewal') {
                // update payments (last = 0)
            }
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
            'last' => true,
            'currency' => 'EUR',
            'transaction_id' => $transactionId,
            'message' => (isset($query['message'])) ? $query['message'] : NULL,
            'data' => (isset($query['data'])) ? $query['data'] : NULL,
        ]);
//file_put_contents('debog_file.txt', print_r($query, true));

        return $payment;
    }

    /*
     * Compute a new member number.
     */
    public function getMemberNumber()
    {
        // Get the highest member number.
        //$lastNumber = Db::table('codalia_membership_members')->max('member_number');
        $lastNumber = Membership::max('member_number');
        // Initialise final letter as ceseda.
        $letter = 'C';

        // Check for honorary member.
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

        //return date('Y').'-'.$newNumber.'-'.$letter;
        $this->member_number = date('Y').'-'.$newNumber.'-'.$letter;
        $this->save();
    }
}
