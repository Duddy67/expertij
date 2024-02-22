<?php

namespace App\Http\Requests\Membership\Sharing;

use Illuminate\Foundation\Http\FormRequest;


class DocumentRequest extends FormRequest
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
        // Check the document uploaded from the document sharing partial.
        return [
            $this->request->get('_field_name') => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:10000',
        ];
    }

    public function attributes()
    {
        // Check the document field name and set both the attribute and name accordingly.
        // N.B: It's not possible to spot indexed field names (ie: item_1, item_2...) as wildcard (item_*) doesn't work for attributes.  
        $attribute = (str_starts_with($this->request->get('_field_name'), 'replace_document_')) ? $this->request->get('_field_name') : 'add_document';
        $message = ($attribute == 'add_document') ? __('labels.generic.add_document') : __('labels.generic.replace_document');

        return [$attribute => $message];
    }
}
