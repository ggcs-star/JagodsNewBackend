<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:60'],
            'last_name'  => ['required', 'string', 'max:60'],
            'email'      => ['required', 'string', Rule::unique("users", "email")->ignore($this->profile), 'email', 'max:100'],
            'phone'      => ['required', 'regex:/^\+?[1-9]\d{1,20}$/'],
            'address'    => ['required', 'max:200'],
            'image'      => 'required|mimes:jpeg,jpg,png,gif|max:3096',
            'username'   => request('username') ? ['required', 'string', Rule::unique("users", "username")->ignore($this->profile), 'max:60'] : ['nullable'],
        ];
    }

}
