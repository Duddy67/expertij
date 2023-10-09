<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('membership.registration', ['only' => ['create', 'store']]);
        $this->middleware('membership.edit', ['only' => ['edit', 'update']]);
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
        // Set indexes for licences, attestations and skills
        $i = $j = $k = 0;
        // Get the user if he already exists.
        $user = (Auth::check()) ? Auth::user() : null;

        return view('themes.'.$page['theme'].'.index', compact('page', 'options', 'user', 'i', 'j', 'k'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        // The user already exists.
        if (Auth::check()) {
            $user = Auth::user();
        }
        else {
            // Create a brand new user.
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('first_name').' '.$request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                //'civility' => $request->input('civility'),
                //'birth_name' => $request->input('birth_name'),
                //'birth_location' => $request->input('birth_location'),
                //'birth_date' => $request->input('_birth_date'),
                //'citizenship_id' => $request->input('citizenship'),
            ]);

            $user->assignRole('registered');
        }

        // Add the membership attributes to the user.
        $user->civility = $request->input('civility');
        $user->birth_name = $request->input('birth_name');
        $user->birth_location = $request->input('birth_location');
        $user->birth_date = $request->input('_birth_date');
        $user->citizenship_id = $request->input('citizenship');
        $user->save();

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
                // First, get the jurisdiction type according to the licence type in order to get the jurisdiction id. 
                $jurisdictionType = ($licenceItem['type'] == 'expert') ? 'appeal_court' : 'court';

                $licence = new Licence([
                    'type' => $licenceItem['type'],
                    'since' => $licenceItem['since'],
                    'jurisdiction_id' => $licenceItem[$jurisdictionType],
                ]);

                // Set the jurisdiction type according to the licence type.
                //$jurisdictionType = ($licence->type == 'expert') ? 'appeal_court' : 'court';
                //$jurisdiction = Jurisdiction::where('id', $licenceItem[$jurisdictionType])->first();
                //$jurisdiction->licences()->save($licence);

                $membership->licences()->save($licence);

                foreach ($licenceItem['attestations'] as $j => $attestationItem) {
                    $attestation = new Attestation([
                        'expiry_date' => $attestationItem['_expiry_date'],
                    ]);

                    $document = $this->uploadDocument($request, $attestationItem['_attestation_file_id'], 'licence_attestation'); 
                    $attestation->document()->save($document);

                    $licence->attestations()->save($attestation);

                    foreach ($attestationItem['skills'] as $skillItem) {
                        $skill = new Skill([
                            'language_id' => $skillItem['alpha_3'],
                            'interpreter' => (isset($skillItem['interpreter'])) ? true : false,
                            'interpreter_cassation' => (isset($skillItem['interpreter_cassation'])) ? true : false,
                            'translator' => (isset($skillItem['translator'])) ? true : false,
                            'translator_cassation' => (isset($skillItem['translator_cassation'])) ? true : false,
                        ]);

                        //$language = Language::where('alpha_3', $skillItem['alpha_3'])->first();
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
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $page = Setting::getPage('membership');
        $options = $this->getOptions();
        // Get the user's membership.
        $membership = Auth::user()->membership;

        return view('themes.'.$page['theme'].'.index', compact('page', 'options', 'membership'));
    }

    /**
     * Update the user's membership in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        // Get the user's membership.
        $membership = Auth::user()->membership;
        $replacements = $updates = [];

        $membership->professional_status = $request->input('professional_status');
        $membership->professional_status_info = $request->input('professional_status_info');
        $membership->since = $request->input('since');
        $membership->siret_number = $request->input('siret_number');
        $membership->naf_code = $request->input('naf_code');
        $membership->save();

        // Check for possible attestation file replacement.
        if ($request->has('professional_attestation')) {
            // Delete the previous attestation.
            if ($membership->professionalAttestation) {
                $membership->professionalAttestation->delete();
            }

            $document = $this->uploadDocument($request, 'professional_attestation'); 
            $membership->professionalAttestation()->save($document);
            $replacements[] = $this->getReplacementData($document, 'attestation-file-button');
            $updates['professional_attestation'] = '';
        }

        $result = ['success' => __('messages.membership.update_success')];

        if (!empty($replacements)) {
            $result['replacements'] = $replacements;
            $result['updates'] = $updates;
        }

        return response()->json($result);
    }

    /**
     * Update the user's membership licences in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLicences(UpdateRequest $request)
    {
        // Get the user's membership.
        $membership = Auth::user()->membership;
        $replacements = $updates = [];

        foreach ($request->input('licences') as $i => $licenceItem) {
            // First, get the jurisdiction type (set as dropdownlist name in the form) according to
            // the licence type in order to get the jurisdiction id. 
            $jurisdictionType = ($licenceItem['type'] == 'expert') ? 'appeal_court' : 'court';

            // Update the licence.
            if (isset($licenceItem['_id'])) {
                $licence = $membership->licences->where('id', $licenceItem['_id'])->first();

                $licence->type = $licenceItem['type'];
                $licence->since = $licenceItem['since'];
                $licence->jurisdiction_id = $licenceItem[$jurisdictionType];
                $licence->save();
            }
            // Create a new licence.
            else {
                $licence = new Licence([
                    'type' => $licenceItem['type'],
                    'since' => $licenceItem['since'],
                    'jurisdiction_id' => $licenceItem[$jurisdictionType],
                ]);

                $membership->licences()->save($licence);
            }

            foreach ($licenceItem['attestations'] as $j => $attestationItem) {
                // Update the attestation.
                if (isset($attestationItem['_id'])) {
                    $attestation = $licence->attestations->where('id', $attestationItem['_id'])->first();

                    $attestation->expiry_date = $attestationItem['_expiry_date'];
                    $attestation->save();
                }
                // Create a new attestation.
                else {
                    $attestation = new Attestation([
                        'expiry_date' => $attestationItem['_expiry_date'],
                    ]);

                    $licence->attestations()->save($attestation);
                }

                // Check for possible attestation file replacement or new attestation.
                if ($request->has($attestationItem['_attestation_file_id'])) {
                    // Delete the previous attestation document if any.
                    if ($attestation->document) {
                        $attestation->document->delete();
                    }

                    $document = $this->uploadDocument($request, $attestationItem['_attestation_file_id'], 'licence_attestation'); 
                    $attestation->document()->save($document);

                    // Get the field index from the attestation file id.
                    preg_match('#(_[0-9]+_[0-9]+)$#', $attestationItem['_attestation_file_id'], $matches);
                    $containerId = 'attestation-file-button'.str_replace('_', '-', $matches[1]);

                    $replacements[] = $this->getReplacementData($document, $containerId);
                    $updates['attestation'.$matches[1]] = '';
                }

                foreach ($attestationItem['skills'] as $skillItem) {
                    // Update the skill.
                    if (isset($skillItem['_id'])) {
                        $skill = $attestation->skills->where('id', $skillItem['_id'])->first();

                        $skill->language_id = $skillItem['alpha_3'];
                        $skill->interpreter = (isset($skillItem['interpreter'])) ? true : false;
                        $skill->interpreter_cassation = (isset($skillItem['interpreter_cassation'])) ? true : false;
                        $skill->translator = (isset($skillItem['translator'])) ? true : false;
                        $skill->translator_cassation = (isset($skillItem['translator_cassation'])) ? true : false;
                        $skill->save();
                    }
                    // Create a new skill.
                    else {
                        $skill = new Skill([
                            'language_id' => $skillItem['alpha_3'],
                            'interpreter' => (isset($skillItem['interpreter'])) ? true : false,
                            'interpreter_cassation' => (isset($skillItem['interpreter_cassation'])) ? true : false,
                            'translator' => (isset($skillItem['translator'])) ? true : false,
                            'translator_cassation' => (isset($skillItem['translator_cassation'])) ? true : false,
                        ]);

                        $attestation->skills()->save($skill);
                    }
                }
            }
        }

        $result = ['success' => __('messages.membership.licences_update_success')];

        if (!empty($replacements)) {
            $result['replacements'] = $replacements;
            $result['updates'] = $updates;
        }

        return response()->json($result);
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
     * Create a new item for the membership. (AJAX)
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

        // Use the registration partial for new items.
        $html = view('themes.'.$page['theme'].'.partials.membership.registration.'.$request->input('_type'), compact('page', 'options', 'i', 'j', 'k'))->render();

        // licence, (no index is needed).
        $index = '';

        if ($type == 'attestation') {
            $index = '-'.$i;
        }

        if ($type == 'skill') {
            $index = '-'.$i.'-'.$j;
        }

        return response()->json(['additions' => [['html' => $html, 'containerId' => $type.'-container'.$index, 'position' => 'beforeend']]]);
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
            $items = [
                'licence' => '\App\Models\Membership\Licence', 
                'attestation' => '\App\Models\Membership\Attestation', 
                'skill' => '\App\Models\Membership\Skill'
            ];

            $items[$type]::where('id', $id)->delete();
        }

        return response()->json(['deletions' => [['targetId' => $type.'-'.$request->input('_index')]], 'success' => __('messages.generic.item_deleted')]);

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

    private function getReplacementData(Document $document, string $containerId)
    {
        $page = Setting::getPage('membership');
        $fileUrl = $document->getUrl();
        $fileName = $document->file_name;
        $html = view('themes.'.$page['theme'].'.partials.membership.edit.attestation-file-button', compact('fileUrl', 'fileName'))->render();

        return ['containerId' => $containerId, 'html' => $html];
    }
}
