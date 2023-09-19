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

    public function addLicence(Request $request)
    {
        // Get the number of licences to use it as new licence index (ie: current index + 1).
        $i = count($request->input('licences'));
        $j = $k = 0;
        $page = Setting::getPage('membership.registration');
        $options = $this->getOptions();
        $html = view('themes.'.$page['theme'].'.partials.membership.registration.licence', compact('page', 'options', 'i', 'j', 'k'))->render();

        return response()->json(['html' => $html, 'destination' => 'licence-container']);
    }

    public function deleteLicence(Request $request, int $id)
    {
        // The licence exists in database.
        if ($id > 0) {
        }

        return response()->json(['target' => 'licence-'.$request->input('_index')]);
    }

    public function addAttestation(Request $request)
    {
        $i = $request->input('_licence_index');
//file_put_contents('debog_file.txt', print_r(count($request->input('licences.'.$i.'.attestations')), true));
        $j = count($request->input('licences.'.$i.'.attestations'));
        $k = 0;
        $page = Setting::getPage('membership.registration');
        $options = $this->getOptions();
        $html = view('themes.'.$page['theme'].'.partials.membership.registration.attestation', compact('page', 'options', 'i', 'j', 'k'))->render();

        return response()->json(['html' => $html, 'destination' => 'attestation-container-'.$i]);
    }

    public function deleteAttestation(Request $request, int $id)
    {
        //
    }

    public function addSkill()
    {
        //
    }

    public function deleteSkill()
    {
        //
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
