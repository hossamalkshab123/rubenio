<?php

namespace App\Http\Requests\Customer;

<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => 'required|unique:customers',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => __('validation.phone_required'),
            'phone.unique' => __('validation.phone_unique'),
        ];
    }
}