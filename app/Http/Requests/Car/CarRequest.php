<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
        return [
            'employee_id' => 'required',
            'destination' => "required",
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => "required",
            "description" => 'required'
        ];
    }
}
