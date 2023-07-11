<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class jobCardVal extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'dept_station' => 'required',
        'dept_time' => 'required|date',
        'train_num' => 'required|numeric|integer',
        'arr_station' => 'required',
        'arr_time' => 'required|date',
        'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'dept_station' => 'Departure Station is required',
            'dept_time.required' => 'DepartureTime is required',
            'dept_time.date_format' => 'departure Time must be valid Time format',
            'train_num.required' => 'Train Number is required',
            'train_num.integer' => 'Train Number must be integer',
            'arr_station.required' => 'Arrival Station is required',
            'arr_time.required' => 'Arrival Time is required',
            'arr_time.date_format' => 'departure Time must be valid Time format',  
            'status' => 'Status must be enum'   
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
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
