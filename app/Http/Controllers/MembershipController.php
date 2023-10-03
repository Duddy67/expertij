<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
use App\Models\Cms\Document;
use App\Http\Requests\Membership\StoreRequest;
use App\Http\Requests\Membership\UpdateRequest;

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
        $this->middleware('auth', ['except' => ['create', 'store', 'createItem', 'deleteItem']]);
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
    public function store(StoreRequest $request)
    {
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'name' => $request->input('first_name').' '.$request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'civility' => $request->input('civility'),
            'birth_name' => $request->input('birth_name'),
            'birth_location' => $request->input('birth_location'),
            'birth_date' => $request->input('_birth_date'),
            'citizenship_id' => $request->input('citizenship'),
        ]);

        $user->assignRole('registered');

        $address = new Address([
            'street' => $request->input('street'),
            'additional_address' => $request->input('additional_address'),
            'postcode' => $request->input('postcode'),
            'city' => $request->input('city'),
            'phone' => $request->input('phone'),
        ]);

        $user->address()->save($address);

        if (!$request->input('associated_member', null)) {
            $membership = new Membership([
                'professional_status' => $request->input('professional_status'),
                'professional_status_info' => $request->input('professional_status_info'),
                'since' => $request->input('since'),
                'siret_number' => $request->input('siret_number'),
                'naf_code' => $request->input('naf_code'),
                'linguistic_training' => $request->input('linguistic_training'),
                'extra_linguistic_training' => $request->input('extra_linguistic_training'),
                'professional_experience' => $request->input('professional_experience'),
                'observations' => $request->input('observations'),
                'why_expertij' => $request->input('why_expertij'),
                // New subscriptions are pending by default.
                'status' => 'pending',
                'owned_by' => $user->id,
            ]);

            $professionalAttestation = $this->uploadDocument($request, 'professional_attestation'); 
            $membership->professionalAttestation()->save($professionalAttestation);
            $user->membership()->save($membership);


            foreach ($request->input('licences') as $i => $licenceItem) {
                $licence = new Licence([
                    'type' => $licenceItem['type'],
                    'since' => $licenceItem['since'],
                ]);

                // Set the jurisdiction type according to the licence type.
                $jurisdictionType = ($licence->type == 'expert') ? 'appeal_court' : 'court';
                $jurisdiction = Jurisdiction::where('id', $licenceItem[$jurisdictionType])->first();
                $jurisdiction->licences()->save($licence);

                $membership->licences()->save($licence);

                foreach ($licenceItem['attestations'] as $j => $attestationItem) {
                    $attestation = new Attestation([
                        'expiry_date' => $attestationItem['_expiry_date'],
                    ]);

                    $document = $this->uploadDocument($request, 'attestation_'.$i.'_'.$j, 'licence_attestation'); 
                    $attestation->document()->save($document);

                    $licence->attestations()->save($attestation);

                    foreach ($attestationItem['skills'] as $skillItem) {
                        $skill = new Skill([
                            'interpreter' => (isset($skillItem['interpreter'])) ? true : false,
                            'interpreter_cassation' => (isset($skillItem['interpreter_cassation'])) ? true : false,
                            'translator' => (isset($skillItem['translator'])) ? true : false,
                            'translator_cassation' => (isset($skillItem['translator_cassation'])) ? true : false,
                        ]);

                        $language = Language::where('alpha_3', $skillItem['alpha_3'])->first();
                        $language->skills()->save($skill);

                        $attestation->skills()->save($skill);
                    }
                }
            }
        }

        if ($photo = $this->uploadDocument($request, 'photo')) {
            $user->photo()->save($photo);
        }

        $lastId = $user->id;

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

    /**
     * Create a new item to the membership. (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON
     */
    public function createItem(Request $request)
    {
        $type = $request->input('_type');
        $newIndex = $request->input('_new_index');

        // Compute the item indexes:
        // $i = licence, $j = attestation, $k = skill.

        $i = ($type == 'licence') ? $newIndex : $request->input('_licence_index');
        // Set $j to zero if the new item is of type "licence"
        $j = ($type == 'attestation') ? $newIndex : 0;
        // Set $j to the given attestation index if the new item is of type "skill".
        $j = ($request->input('_attestation_index', null)) ? $request->input('_attestation_index') : $j;
        $k = ($type == 'skill') ? $newIndex : 0;

        $page = Setting::getPage('membership.registration');
        $options = $this->getOptions();

        $html = view('themes.'.$page['theme'].'.partials.membership.registration.'.$request->input('_type'), compact('page', 'options', 'i', 'j', 'k'))->render();

        // licence, (no index is needed).
        $index = '';

        if ($type == 'attestation') {
            $index = '-'.$i;
        }

        if ($type == 'skill') {
            $index = '-'.$i.'-'.$j;
        }

        return response()->json(['html' => $html, 'destination' => $type.'-container'.$index]);
    }

    /**
     * Delete a given item from the membership. (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JSON
     */
    public function deleteItem(Request $request, int $id)
    {
        $type = $request->input('_type');

        // The item exists in database.
        if ($id > 0) {
        }

        return response()->json(['target' => $type.'-'.$request->input('_index')]);
    }

    /*
     * Creates a Document associated with the given uploaded file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Cms\Document
     */
    private function uploadDocument(Request $request, string $inputName, string $field = '')
    {
        if ($request->hasFile($inputName) && $request->file($inputName)->isValid()) {
            $document = new Document;
            $field = (empty($field)) ? $inputName : $field;
            $document->upload($request->file($inputName), $field);

            return $document;
        }

        return null;
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
