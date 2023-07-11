<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class updateDutyRosterVal extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'duration' => [
                'nullable',
                'date_format:H:i:s',
            ],
            'start_time' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ],
            'end_time' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ]
        ];
    }

    public function messages()
    {
        return [
            'duration.date_format' => 'Duration must be valid Time format (H:i:s)',
            'start_time.date_format' => 'Start time must have valid date format (Y-m-d H:i:s)',
            'end_time.date_formate' => 'End time must have valid date format (Y-m-d H:i:s)',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => $validator->errors(),
        ], 422));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
