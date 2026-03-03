<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SocialRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'provider_name' => ['required', 'string'],
            'provider_id' => ['required', 'string'],
            'device_name' => ['required', 'string'],
            'image' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns', 'unique:users'],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email already registered. Try login',
        ];
    }
}
