<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
      'name'                  => 'required',
      'role'                  => 'required',
      'username'              => 'sometimes|required|email|unique:users,id,' . $this->id . ',username',
      'email'                 => 'sometimes|required|email|unique:users,id,' . $this->id . ',email',
    ];
  }
}
