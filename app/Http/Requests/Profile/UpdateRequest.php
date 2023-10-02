<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
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
        // Get the current user.
        $user = Auth::user();
        // First get the user validation rules.
        $rules = User::getUpdateValidationRules($user->id);

        if ($user->membership()->exists()) {
            // Add the membership validation rules.
            $rules['civility'] = 'required';
            $rules['citizenship'] = 'required';
            $rules['birth_name'] = 'required|max:50';
            $rules['birth_date'] = 'required';
            $rules['birth_location'] = 'required|max:50';
            // address
            $rules['street'] = 'required|max:150';
            $rules['postcode'] = 'required|digits:5';
            $rules['city'] = 'required|max:150';
            $rules['phone'] = 'required|max:20';
        }

        return $rules;
    }
}
