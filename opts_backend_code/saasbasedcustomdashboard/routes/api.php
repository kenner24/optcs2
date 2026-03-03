<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ConnectorController;
use App\Http\Controllers\Api\V1\EmailVerificationNotificationController;
use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\GoogleSheetController;
use App\Http\Controllers\Api\V1\OverviewController;
use App\Http\Controllers\Api\V1\QuickBooksController;
use App\Http\Controllers\Api\V1\ReportGoalController;
use App\Http\Controllers\Api\V1\ReportsController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\SalesForceController;
use App\Http\Controllers\Api\V1\StaffController;
use App\Http\Controllers\Api\V1\StaffPermissionController;
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Controllers\Api\V1\VerifyEmailController;
use App\Http\Controllers\WebsitePageManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/social-login', [AuthController::class, 'userSocialLogin']);
Route::post('/register-social-account', [AuthController::class, 'userSocialRegister']);
Route::post('/mail-verification-notification', EmailVerificationNotificationController::class)->middleware(['throttle:6,1']);
Route::get('/verify-email', VerifyEmailController::class)->middleware(['throttle:6,1']);
Route::get('/page-content', [WebsitePageManagementController::class, 'getPageContent'])->middleware(['throttle:6,1']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendForgotPasswordEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

// compute api endpoints
Route::get('/compute/leads-data', [SalesForceController::class, 'fetchLeadsData']);
Route::get('/compute/opportunities-data', [SalesForceController::class, 'fetchOpportunitiesData']);
Route::get('/compute/quick-books-data', [QuickBooksController::class, 'fetchData']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('/user-logout', [AuthController::class, 'userLogout']);

    // staff routes
    Route::post('/staff-add', [StaffController::class, 'staffAdd']);
    Route::get('/staff-list', [StaffController::class, 'getStaffList']);
    Route::post('/change-staff-status', [StaffController::class, 'activateDeactivateAcc']);
    Route::put('/staff-edit', [StaffController::class, 'editStaffDetail']);
    Route::delete('/staff-delete', [StaffController::class, 'staffDelete']);

    //user profile routes
    Route::put('/user-profile-update', [UserProfileController::class, 'profileUpdate']);
    Route::get('/user-profile', [UserProfileController::class, 'getProfile']);
    Route::post('/user-change-password', [UserProfileController::class, 'changePassword']);

    // role permission routes
    Route::put('/change-staff-permission', [StaffPermissionController::class, 'changePermission']);

    // connectors
    Route::get('/connectors/authorization-endpoint', [ConnectorController::class, 'getAuthorizationUrl']);
    Route::post('/connectors/add', [ConnectorController::class, 'exchangeTokenAndSave']);
    Route::post('/connectors/add-preference', [ConnectorController::class, 'saveConnectorPreference']);
    Route::get('/connectors/fetch-preferences', [ConnectorController::class, 'fetchConnectorPreference']);

    // Reports
    Route::get("/reports", [ReportsController::class, "index"]);
    Route::get("/fetch-report-goals", [ReportGoalController::class, 'fetchGoals']);
    Route::post("/save-report-goals", [ReportGoalController::class, 'saveReportGoals']);


    //quickbooks
    Route::get("/quickbook-budget-list", [QuickBooksController::class, 'fetchBudgetList']);

    // google sheets
    Route::get('/google-spreadsheet-list', [GoogleSheetController::class, 'getSpreadSheetList']);
    Route::get('/fetch-spreadsheet-sheets-list/{id}', [GoogleSheetController::class, 'fetchSpreadSheetSheetList']);
    Route::get('/fetch-sheets-headers/{id}', [GoogleSheetController::class, 'fetchSpreadSheetHeaders']);

    // overview
    Route::get('/overview', [OverviewController::class, 'index']);
});
