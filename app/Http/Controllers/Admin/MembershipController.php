<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Form;
use App\Traits\CheckInCheckOut;
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
    use Form;

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
        //$this->middleware('admin.memberships');
        $this->item = new Membership;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
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
    public function update(Request $request, $id)
    {
        //
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
}
