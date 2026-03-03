<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ReportNameEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveReportGoalsRequest extends FormRequest
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
        $temp = 'required|integer';
        return [
            "year" => [
                "required",
                "numeric",
                "integer",
                "digits:4",
            ],
            'report_name' => [
                'required',
                Rule::in([
                    ReportNameEnum::Submitted_Production->value,
                    ReportNameEnum::YTD_Submitted_Production->value,
                    ReportNameEnum::Paid_Annuity->value,
                    ReportNameEnum::Paid_Annuity_Vs_DTI->value,
                    ReportNameEnum::Pending_Business->value,
                    ReportNameEnum::Open_Opportunities->value,
                    ReportNameEnum::Leads_Generated->value,
                    ReportNameEnum::First_Appointment_Scheduled->value,
                    ReportNameEnum::Stick_Ratio_Show_Rate->value,
                    ReportNameEnum::Seminar_Responses_Ratio->value,
                    ReportNameEnum::Cost_Per_Client->value,
                    ReportNameEnum::Profitability_Percentage->value,
                    ReportNameEnum::Days_To_Issue->value,
                    ReportNameEnum::New_Assets_From_Existing_Clients->value,
                ]),
            ],
            'goals' => 'required|array',
            'goals.*' => 'required|size:12',
            'goals.Jan' => $temp,
            'goals.Feb' => $temp,
            'goals.Mar' => $temp,
            'goals.Apr' => $temp,
            'goals.May' => $temp,
            'goals.Jun' => $temp,
            'goals.Jul' => $temp,
            'goals.Aug' => $temp,
            'goals.Sep' => $temp,
            'goals.Oct' => $temp,
            'goals.Nov' => $temp,
            'goals.Dec' => $temp,
        ];
    }
}
