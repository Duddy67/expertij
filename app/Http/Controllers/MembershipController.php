<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\User;
use App\Models\Cms\Setting;

class MembershipController extends Controller
{
    /*
     * Instance of the membership model.
     */
    protected $item = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->item = new Membership;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = Setting::getPage('membership.registration');
        $options = $this->getOptions();
        $i = $j = $k = 0;

        return view('themes.'.$page['theme'].'.index', compact('page', 'options', 'i', 'j', 'k'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function addItem(Request $request)
    {
        $type = $request->input('_type');

//file_put_contents('debog_file.txt', print_r($request->all(), true));
        $i = ($type == 'licence') ? count($request->input('licences')) : $request->input('_licence_index');
        $j = ($type == 'attestation') ? count($request->input('licences.'.$i.'.attestations')) : 0;
        $j = ($request->input('_attestation_index', null)) ? $request->input('_attestation_index') : $j;
        $k = ($type == 'skill') ? count($request->input('licences.'.$i.'.attestations.'.$j.'.skills')) : 0;

        $page = Setting::getPage('membership.registration');
        $options = $this->getOptions();

        $html = view('themes.'.$page['theme'].'.partials.membership.registration.'.$request->input('_type'), compact('page', 'options', 'i', 'j', 'k'))->render();

        $index = '';

        if ($type == 'attestation') {
            $index = '-'.$i;
        }

        if ($type == 'skill') {
            $index = '-'.$i.'-'.$j;
        }

        return response()->json(['html' => $html, 'destination' => $type.'-container'.$index]);
    }

    public function deleteItem(Request $request, int $id)
    {
        $type = $request->input('_type');

        // The item exists in database.
        if ($id > 0) {
        }

        return response()->json(['target' => $type.'-'.$request->input('_index')]);
    }

    private function getOptions(Membership $membership = null): array
    {
        $options = [];
        $membership = ($membership) ? $membership : new Membership;

        $options['licence_type'] = $membership->getLicenceTypeOptions();
        $options['since'] = $membership->getSinceOptions();
        $options['professional_status'] = $membership->getProfessionalStatusOptions();
        $options['citizenship'] = $membership->getCitizenshipOptions();
        $options['civility'] = $membership->getCivilityOptions();
        $options['language'] = $membership->getLanguageOptions();
        $options['jurisdictions'] = $membership->getJurisdictionOptions();

        return $options;
    }
}
