<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpadateRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'sometimes|required|email|unique:ms_customers,id,' . $this->id . ',email',
            'ktp' => 'required|numeric',
            'image' => 'sometimes|mimes:jpg,png,jpeg'
        ];
    }
}
