<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class EditDetailsRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'company_name' => ['required', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'image' => [
                'nullable',
                File::types(['jpeg', 'jpg', 'png'])
                    ->max(5 * 1024)
            ],
            'work_email' => ['required', 'email:rfc,dns'],
            'total_employees' => ['required', 'numeric', 'integer'],
            'domain_sector' => ['required', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore(Crypt::decrypt(request()->get('id')))
            ],
        ];
    }
}
