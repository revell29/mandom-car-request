<?php

namespace App\Http\Requests\Laundry;

use Illuminate\Foundation\Http\FormRequest;

class LaundryUpdateRequest extends FormRequest
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
            'price' => 'required|numeric',
            // 'image' => 'sometimes|mimes:jpg,jpeg,png'
        ];
    }
}
