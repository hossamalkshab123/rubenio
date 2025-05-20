<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => __('validation.phone_required'),
            'password.required' => __('validation.password_required'),
        ];
    }
}