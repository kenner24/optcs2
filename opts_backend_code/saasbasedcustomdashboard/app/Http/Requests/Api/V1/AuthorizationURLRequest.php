<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ConnectorEnum;
use App\Enums\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorizationURLRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can(PermissionEnum::CONNECTOR->value)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "connector_type" => [
                "required",
                Rule::in([
                    ConnectorEnum::GOOGLESHEET->value,
                    ConnectorEnum::QUICKBOOKS->value,
                    ConnectorEnum::SALESFORCE->value
                ])
            ]
        ];
    }
}
