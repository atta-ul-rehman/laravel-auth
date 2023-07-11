<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class updateJobCardVal extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dept_station' => 'nullable|string',
            'dept_time' => 'nullable|date_format:Y-m-d H:i:s',
            'train_num' => 'nullable|numeric|integer',
            'arr_station' => 'nullable|string',
            'arr_time' => 'nullable|date_format:Y-m-d H:i:s',
            'status' =>  ['nullable', 'in:Un-assigned, Assigned, In-progress, Completed, Expired'],
        ];
    }

    public function messages()
    {
        return [
            'dept_station.string' => 'Departure Station is must be string',
            'dept_time.date_format' => 'departure Time must be valid Time format',
            'train_num.integer' => 'Train Number must be integer',
            'arr_station.required' => 'Arrival Station is must be string',
            'arr_time.date_format' => 'departure Time must be valid Time format',  
            'status.in' => 'The selected status is invalid.',   
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
