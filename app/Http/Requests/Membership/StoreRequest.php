<?php

namespace App\Http\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // First get the user validation rules.
        $rules = User::getValidationRules();

        // then add the membership validation rules.

        // profile
        $rules['civility'] = 'required';
        $rules['citizenship'] = 'required';
        $rules['birth_name'] = 'required|max:50';
        $rules['birth_date'] = 'required';
        $rules['birth_location'] = 'required|max:50';
        // address
        $rules['street'] = 'required|max:150';
        $rules['postcode'] = 'required|numeric|size:5';
        $rules['city'] = 'required|max:150';
        $rules['phone'] = 'required|max:20';

        // licences, attestations, skills

        $licences = $this->request->all('licences');
        // Loop through attestations in each licence and set a rule accordingly.
        foreach ($licences as $i => $licence) {
            foreach ($licence['attestations'] as $j => $attestation) {
                $rules['attestation_'.$i.'_'.$j] = 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
            }
        }

        $rules['licences.*.since'] = 'required';
        $rules['licences.*.appeal_court'] = 'required_if:licences.*.type,expert';
        $rules['licences.*.court'] = 'required_if:licences.*.type,ceseda';
        $rules['licences.*.attestations.*.expiry_date'] = 'required';
        $rules['licences.*.attestations.*.skills.*.alpha_3'] = 'required';
        $rules['licences.*.attestations.*.skills.*.interpreter'] = 'required_without:licences.*.attestations.*.skills.*.translator';
        $rules['licences.*.attestations.*.skills.*.translator'] = 'required_without:licences.*.attestations.*.skills.*.interpreter';
        // professional status
        $rules['professional_status'] = 'required';
        $rules['professional_status_info'] = 'required_if:professional_status,other|max:50';
        $rules['since'] = 'required';
        $rules['siret_number'] = 'required|numeric|size:14';
        $rules['naf_code'] = 'required|size:5';
        $rules['professional_attestation'] = 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
        $rules['linguistic_training'] = 'required';
        $rules['linguistic_training'] = 'required';
        $rules['extra_linguistic_training'] = 'required';
        $rules['professional_experience'] = 'required';
        $rules['why_expertij'] = 'required';

        return $rules;
    }

    public function messages()
    {
        return [
            'licences.*.attestations.*.skills.*.interpreter.required_without' => __('messages.membership.interpreter_required'), 
            'licences.*.attestations.*.skills.*.translator.required_without' => __('messages.membership.translator_required'), 
            'professional_status_info.required_if' => __('messages.membership.professional_status_info_required'), 
            'licences.*.appeal_court.required_if' => __('messages.membership.appeal_court_required'), 
            'licences.*.court.required_if' => __('messages.membership.court_required'), 
        ];
    }

    public function attributes()
    {
        $rules = [
            'birth_name' => __('labels.user.birth_name'), 
            'birth_location' => __('labels.user.birth_location'), 
            'citizenship' => __('labels.user.citizenship'), 
            'postcode' => __('labels.address.postcode'), 
            'professional_status' => __('labels.membership.professional_status'), 
            'professional_status_info' => __('labels.membership.professional_status_info'), 
            'professional_attestation' => __('labels.membership.professional_attestation'), 
            'since' => __('labels.generic.since'), 
            'naf_code' => __('labels.membership.naf_code'), 
            'linguistic_training' => __('labels.membership.linguistic_training'), 
            'extra_linguistic_training' => __('labels.membership.extra_linguistic_training'), 
            'professional_experience' => __('labels.membership.professional_experience'), 
            'why_expertij' => __('labels.membership.why_expertij'), 
            'licences.*.since' => __('labels.generic.since'), 
            'licences.*.appeal_court' => __('labels.membership.appeal_court'), 
            'licences.*.court' => __('labels.membership.court'), 
            'licences.*.attestations.*.expiry_date' => __('labels.membership.expiry_date'), 
            'licences.*.attestations.*.skills.*.alpha_3' => __('labels.membership.language'), 
            'attestation_.*._.*' => __('labels.generic.attestation'), 
        ];

        $licences = $this->request->all('licences');
        // Loop through attestations in each licence and set a label accordingly.
        foreach ($licences as $i => $licence) {
            foreach ($licence['attestations'] as $j => $attestation) {
                $rules['attestation_'.$i.'_'.$j] = __('labels.generic.attestation'); 
            }
        }

        return $rules;
    }
}
