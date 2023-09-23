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
        // licences
        //$rules['licences.*.type'] = 'required';
        $rules['licences.*.since'] = 'required';
        $rules['licences.*.appeal_court'] = 'required_if:licences.*.type,expert';
        $rules['licences.*.court'] = 'required_if:licences.*.type,ceseda';
        $rules['licences.*.attestations.*.expiry_date'] = 'required';
        $rules['licences.*.attestations.*.skills.*.alpha_3'] = 'required';
        // professional status
        $rules['professional_status'] = 'required';
        $rules['professional_status_info'] = 'required_if:professional_status,other|max:50';
        $rules['since'] = 'required';
        $rules['siret_number'] = 'required|numeric|size:14';
        $rules['naf_code'] = 'required|size:5';
        $rules['linguistic_training'] = 'required';
        $rules['extra_linguistic_training'] = 'required';
        $rules['professional_experience'] = 'required';
        $rules['why_expertij'] = 'required';

        return $rules;
    }
}
