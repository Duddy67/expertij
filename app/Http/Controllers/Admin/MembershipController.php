<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Form;
use App\Traits\CheckInCheckOut;
use App\Traits\Emails;
use App\Traits\Renewal;
use App\Models\Membership;
use App\Models\Membership\Licence;
use App\Models\Membership\Attestation;
use App\Models\Membership\Skill;
use App\Models\Membership\Jurisdiction;
use App\Models\Membership\Language;
use App\Models\User;
use App\Models\User\Citizenship;
use App\Models\Cms\Setting;
use App\Models\Cms\Address;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Membership\Setting as MembershipSetting;
class MembershipController extends Controller
{
    use Form, Emails, Renewal;

    /*
     * Instance of the Membership model, (used in the Form trait).
     */
    protected $item = null;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.memberships');
        $this->item = new Membership;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $columns = $this->getColumns();
        $actions = $this->getActions('list');
        $filters = $this->getFilters($request);

        foreach ($filters as $filter) {
            // Add an extra status.
            if ($filter->name == 'statuses') {
                $filter->options[] = ['value' => 'pending_offline_payment', 'text' => __('labels.membership.pending_offline_payment')];
            }
        }

        $items = Membership::getMemberships($request);
        $rows = $this->getRows($columns, $items);
        $query = $request->query();
        $url = ['route' => 'admin.memberships', 'item_name' => 'membership', 'query' => $query];
        $lastReminderDate = Setting::getValue('flags', 'last_reminder_date', 0, Membership::class);
        $lastReminderDate = ($lastReminderDate) ? Carbon::create($lastReminderDate)->format('d/m/Y H:i') : __('labels.generic.none');

        return view('admin.membership.list', compact('items', 'columns', 'rows', 'actions', 'filters', 'url', 'lastReminderDate', 'query'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id): \Illuminate\View\View
    {
        $membership = $this->item = Membership::find($id);

        if (!$membership->canAccess()) {
            return redirect()->route('admin.memberships.index')->with('error',  __('messages.generic.access_not_auth'));
        }

        if ($membership->checked_out && $membership->checked_out != auth()->user()->id && !$membership->isUserSessionTimedOut()) {
            return redirect()->route('admin.memberships.index')->with('error',  __('messages.generic.checked_out'));
        }

        $membership->checkOut();

        $except = ($membership->member_number) ? [] : ['member_number', 'member_since'];

        $fields = $this->getFields($except);
        $except = (!$membership->canEdit()) ? ['destroy', 'save', 'saveClose'] : [];
        $actions = $this->getActions('form', $except);
        $dateFormat = Setting::getValue('app', 'date_format');
        // Add the id parameter to the query.
        $query = array_merge($request->query(), ['membership' => $id]);

        $options['licence_type'] = $membership->getLicenceTypeOptions();
        $options['since'] = $membership->getSinceOptions();
        $options['language'] = $membership->getLanguageOptions();
        $options['jurisdictions'] = $membership->getJurisdictionOptions();
        $options['citizenship'] = $membership->getCitizenshipOptions();
        $options['civility'] = $membership->getCivilityOptions();

        return view('admin.membership.form', compact('membership', 'options', 'fields', 'actions', 'dateFormat', 'query'));
    }

    /**
     * Checks the record back in.
     *
     * @param  Request  $request
     * @param  \App\Models\Membership $membership (optional)
     * @return Response
     */
    public function cancel(Request $request, Membership $membership = null)
    {
        if ($membership) {
            $membership->safeCheckIn();
        }

        return redirect()->route('admin.memberships.index', $request->query());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Membership $membership)
    {
        if ($membership->checked_out != auth()->user()->id) {
            $request->session()->flash('error', __('messages.generic.user_id_does_not_match'));
            return response()->json(['redirect' => route('admin.memberships.index', $request->query())]);
        }

        if (!$membership->canEdit()) {
            $request->session()->flash('error', __('messages.generic.edit_not_auth'));
            return response()->json(['redirect' => route('admin.memberships.index', $request->query())]);
        }

        $oldStatus = null;

        if ($request->has('status')) {
            $oldStatus = $membership->getOriginal('status');
            $membership->status = $request->input('status');

            if ($request->input('status') == 'cancelled' || $request->input('status') == 'revoked') {
                // Cancel the possible payment for this membership.
                $payment = $membership->getLastPayment();

                if ($payment) {
                    $payment->status = 'cancelled';
                    $payment->save();
                }
            }
        }

        $membership->updated_by = auth()->user()->id;
        $membership->save();

        // The status has changed.
        if ($oldStatus && $oldStatus != $membership->status) {
            // Informs the member or applicant about the status change.
            $this->statusChange($membership);
        }

        if ($request->input('_close', null)) {
            $membership->safeCheckIn();
            // Store the message to be displayed on the list view after the redirect.
            $request->session()->flash('success', __('messages.membership.update_success'));
            return response()->json(['redirect' => route('admin.memberships.index', $request->query())]);
        }

        return response()->json(['success' => __('messages.membership.update_success'), 'function' => 'setStatuses','updates' => $this->getFieldValuesToUpdate($request)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Checks in one or more memberships.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function massCheckIn(Request $request)
    {
        $messages = CheckInCheckOut::checkInMultiple($request->input('ids'), '\\App\\Models\\Membership');

        return redirect()->route('admin.memberships.index', $request->query())->with($messages);
    }

    /*
     * Check the renewal period and send emails to the members accordingly. 
     */
    public function renewal(Request $request)
    {
        $renewal = $this->checkRenewal();

        // The renewal period has started.
        if ($renewal == 'start_renewal') {
            // Send emails to the members.
            $this->renewalAlert('pending-renewal');
        }

        return redirect()->route('admin.memberships.index', $request->query())->with('info', __('messages.membership.'.$renewal));
    }

    public function renewalReminder(Request $request)
    {
        // Do not send reminder during the renewal period.
        if ($this->isRenewalPeriod()) {
            return redirect()->route('admin.memberships.index', $request->query())->with('info', __('messages.membership.renewal_period_no_reminders'));
        }

        // Alert the members who haven't paid their membership fee yet.
        $this->renewalAlert('pending-renewal-reminder');

        // Update the last reminder date.
        MembershipSetting::setLastReminderDate(Carbon::now(Setting::getValue('app', 'timezone'))->format('Y-m-d H:i'));

        return redirect()->route('admin.memberships.index', $request->query())->with('info', __('messages.membership.reminder_emails_sent'));
    }

    public function export(Request $request)
    {
        $file = Membership::createExportList($request);

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            // Remove the file from the server.
            unlink($file);
            exit;
        }
    }

    /**
     *  Sends notification emails to the decision makers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendEmails(Request $request, Membership $membership)
    {
        if ($this->decisionMakersAlert($membership->user)) {
            $membership->sending_emails = true;
            $membership->save();

            return response()->json(['success' => __('messages.membership.alert_decision_makers'), 'updates' => ['sendEmails' => true]]);
        }
        else {
            return response()->json(['warning' => __('messages.generic.cannot_send_email')]);
        }
    }

    /*
     * Set the offline payment status as well as the membership status according to the new payment status. 
     */
    public function setPayment(Request $request, Membership $membership)
    {
        $payment = $membership->getLastPayment();

        // A payment for subscription is completed. The user is now member.
        if ($request->input('payment_status') == 'completed' && str_starts_with($payment->item, 'subscription')) {
            // Check if the user is a new member.
            $isNew = ($membership->member_number) ? false : true;

            if ($isNew) {
                $membership->member_number = $membership->getMemberNumber();
                $membership->member_since = Carbon::now();
            }

            $membership->status = 'member';
            $membership->save();

            // Informs the user about their member status.
            $this->member($membership, $isNew);
        }

        $payment->status = $request->input('payment_status');
        $payment->save();

        // Set insurance.

        if ($payment->item == 'subscription') {
            // Cancel a possible previous insurance.
            $membership->cancelInsurance();
        }
        else {
            // Get the insurance code from the item variable.
            $code = substr($payment->item, -2);
            // Add the insurance to the membership.
            $membership->setInsurance($code);
        }

        // Create the invoices.

        if ($payment->status == 'completed') {
            // Create an invoice for the payment according to the purchased item.
            if ($payment->item == 'subscription') {
                $membership->createSubscriptionInvoice($payment);
            }
            elseif (str_starts_with($payment->item, 'insurance_')) {
                $membership->createInsuranceInvoice($payment);
            }
            // The member has paid for both subscription and insurance. (item = subscription_insurance_xx).
            else {
                // Create one invoice for each item.
                $membership->createSubscriptionInvoice($payment);
                $membership->createInsuranceInvoice($payment);
            }
        }

        // Informs the user about their payment.
        $this->payment($membership);

        return response()->json(['success' => __('messages.generic.payment_update_success'), 'function' => 'afterPayment']);
    }
}
