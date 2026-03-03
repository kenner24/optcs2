<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffPermissionController extends Controller
{

    public function __construct(
        private StaffService $staffService
    ) {
        $this->middleware(['permission:' . PermissionEnum::SUB_ACCOUNT->value]);
    }

    public function changePermission(Request $request)
    {
        $payload = $request->validate([
            "assign_permission" => ["required", "boolean"],
            "permission_type" => [
                "required",
                Rule::in([
                    PermissionEnum::CONNECTOR->value,
                    PermissionEnum::REPORTS->value,
                    PermissionEnum::SUB_ACCOUNT->value,
                ])
            ],
            "user_id" => ["required", "numeric", "integer"]
        ]);

        $user = auth()->user();
        $companyId = $user->id;
        $findUser = $this->staffService->getStaffDetails($payload['user_id'], $companyId);

        if (isset($findUser)) {
            // if true
            if ($payload['assign_permission']) {
                if ($payload['permission_type'] === PermissionEnum::CONNECTOR->value) {
                    $findUser->givePermissionTo(PermissionEnum::CONNECTOR->value);
                } elseif ($payload['permission_type'] === PermissionEnum::REPORTS->value) {
                    $findUser->givePermissionTo(PermissionEnum::REPORTS->value);
                } elseif ($payload['permission_type'] === PermissionEnum::SUB_ACCOUNT->value) {
                    $findUser->givePermissionTo(PermissionEnum::SUB_ACCOUNT->value);
                }
            }

            // if false
            if (!$payload['assign_permission']) {
                if ($payload['permission_type'] === PermissionEnum::CONNECTOR->value) {
                    $findUser->revokePermissionTo(PermissionEnum::CONNECTOR->value);
                } elseif ($payload['permission_type'] === PermissionEnum::REPORTS->value) {
                    $findUser->revokePermissionTo(PermissionEnum::REPORTS->value);
                } elseif ($payload['permission_type'] === PermissionEnum::SUB_ACCOUNT->value) {
                    $findUser->revokePermissionTo(PermissionEnum::SUB_ACCOUNT->value);
                }
            }

            return $this->sendResponse("Permissions updated successfully", []);
        }

        return $this->sendError("Member not found");
    }
}
