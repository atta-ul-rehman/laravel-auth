<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Modules\Auth\Rules\MatchOldPass;
class updatePassVal extends FormRequest
{
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
        return [
            'current_password' => ['required', new MatchOldPass],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

     public function messages()
     {
         return [
            'current_password.required' => 'Please enter your current password.',
            'current_password.match_old_pass' => 'The current password you entered is incorrect.',
            'new_password.required' => 'Please enter a new password.',
            'new_confirm_password.same' => 'The new password confirmation does not match the new password.',
         ];
     }
 

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => $validator->errors(),
        ], 422));
    }
}
