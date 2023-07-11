<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class resetPassVal extends FormRequest
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
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'token.required' => 'Token is required',
            'email.email' => 'Email is wrong',
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 5 characters long',
            'password.confirmed' => 'Password must match',
            'passwords.reset' => 'Your password has been reset!',
            'passwords.sent' => 'We have emailed your password reset link!',
            'passwords.throttled' => 'Please wait before retrying.',
            'passwords.token' => 'The password reset token is invalid or has expired.',
            'passwords.user' => "We can't find a user with that email address.",
            'passwords.passwords.token' => 'The password reset token is invalid or has expired.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422));
    }
}
