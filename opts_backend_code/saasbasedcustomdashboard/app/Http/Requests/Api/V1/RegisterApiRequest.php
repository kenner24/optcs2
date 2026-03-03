<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterApiRequest extends FormRequest
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
            'name'      => ['required', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'email'     => ['required', 'max:255', 'email:rfc,dns', 'unique:users'],
            'password'  => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'username'        => ['required', 'alpha_num:ascii', 'min:3', 'max:255'],
        ];
    }
}
