<?php

namespace App\Http\Requests\Food;

use Illuminate\Foundation\Http\FormRequest;

class FoodUpdateRequest extends FormRequest
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
            'image' => 'sometimes|mimes:jpg,jpeg,png',
            'price' => 'required|numeric',
            'category_id' => 'required'
        ];
    }

    /**
     * Custom Message
     * 
     * @return array 
     **/
    public function messages()
    {
        return [
            'category_id.required' => 'Please fill the required fields.'
        ];
    }
}
