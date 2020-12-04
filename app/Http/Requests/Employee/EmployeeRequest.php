<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'email' => 'sometimes|required|email|unique:users,id,' . $this->id . ',email',
            'departement_id' => 'required',
            'birth_date' => 'required',
            'city' => 'required',
            'address' => 'required',
            'hp' => 'required',
            'password' => 'required',
            'password_confirmation' => "sometimes|required|same:password"
        ];
    }

    public function messages()
    {
        return [
            'departement_id.required' => 'Departement is required.'
        ];
    }
}
