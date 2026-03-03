<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }
    public function dashboardView()
    {
        $usersCount = $this->userService->getUsersCount();
        $companyCount = $this->userService->getUsersCountByRole(UserTypeEnum::COMPANY->value);
        $staffCount = $this->userService->getUsersCountByRole(UserTypeEnum::STAFF->value);

        return view('superAdmin.dashboard', [
            "totalUser" => $usersCount,
            "totalCompany" => $companyCount,
            "totalStaff" => $staffCount,
            "totalLogs" => 0,
            "totalPlans" => 0,
            "totalSubscriptions" => 0,
            "totalRevenue" => 0,
            "pendingAmt" => 0
        ]);
    }
}
