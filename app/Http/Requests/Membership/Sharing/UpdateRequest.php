<?php

namespace App\Http\Requests\Membership\Sharing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\AccessLevel;


class UpdateRequest extends FormRequest
{
    use AccessLevel;

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
        $rules['name'] = 'required';
        $rules['licence_types'] = 'required';

	if (auth()->user()->getRoleLevel() > $this->sharing->getOwnerRoleLevel() || $this->sharing->owned_by == auth()->user()->id) {
	    $rules['access_level'] = 'required';
	    $rules['owned_by'] = 'required';
	}

        if ($this->sharing->canChangeStatus()) {
            $rules['status'] = 'required';
        }

	return $rules;
    }
}
