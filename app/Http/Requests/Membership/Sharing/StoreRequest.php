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

      //file_put_contents('debog_file.txt', print_r($this->request->all(), true));
        foreach ($this->request->all() as $key => $input) {
            if (str_starts_with($key, 'document_')) {
                $rules[$key] = 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
            }
        }

        $rules['document_0'] = 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000';
        $rules['name'] = 'required'; 
        $rules['access_level'] = 'required';
        //'permission' => 'required',
        $rules['owned_by'] = 'required';

        return $rules;
        /*return [
	    'name' => [
		'required',
		'regex:/^[a-z0-9-]{3,}$/',
		'unique:groups'
	    ],
	    'access_level' => 'required',
	    //'permission' => 'required',
	    'owned_by' => 'required'
        ];*/
    }
}
