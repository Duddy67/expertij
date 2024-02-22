<?php

namespace App\Http\Requests\Membership\Sharing;

use Illuminate\Foundation\Http\FormRequest;


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
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = 'required'; 
        $rules['licence_types'] = 'required';
        $rules['access_level'] = 'required';
        $rules['status'] = 'required';
        $rules['owned_by'] = 'required';
        $rules['document_1'] = 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
        $rules['document_2'] = 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
        $rules['document_3'] = 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';

        return $rules;
    }
}
