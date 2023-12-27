<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Form;
use App\Traits\CheckInCheckOut;
use App\Traits\Emails;
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

class MembershipController extends Controller
{
    use Form, Emails;

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
        $items = Membership::getMemberships($request);
        $rows = $this->getRows($columns, $items);
        $query = $request->query();
        $url = ['route' => 'admin.memberships', 'item_name' => 'membership', 'query' => $query];

        return view('admin.membership.list', compact('items', 'columns', 'rows', 'actions', 'filters', 'url', 'query'));
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
        //$this->setFieldValues($fields, $membership);
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

//file_put_contents('debog_file.txt', print_r($messages, true));
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
        }

        $membership->updated_by = auth()->user()->id;
        $membership->save();

        // The status has changed.
        if ($oldStatus && $oldStatus != $membership->status) {
            // Informs the member/applicant about the status change.
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

    /**
     *  Sends notification emails to the decision makers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendEmails(Request $request, Membership $membership)
    {
        //file_put_contents('debog_file.txt', print_r($membership->user, true));
        if ($this->decisionMakersAlert($membership->user)) {
            $membership->sending_emails = true;
            $membership->save();

            return response()->json(['success' => __('messages.membership.alert_decision_makers'), 'updates' => ['sendEmails' => true]]);
        }
        else {
            return response()->json(['warning' => __('messages.generic.cannot_send_email')]);
        }
    }
}
