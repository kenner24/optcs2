<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuthController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\CompanyReportController;
use App\Http\Controllers\SuperAdmin\CompanyStaffController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\ProfileController;
use App\Http\Controllers\WebsitePageManagementController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin/dashboard');
    }
    // return view('welcome');
    return redirect('/login');
});

Route::get('/login', [SuperAdminAuthController::class, 'loginView'])->name('login');
Route::post('/login', [SuperAdminAuthController::class, 'login']);
Route::any('/logout', [SuperAdminAuthController::class, 'logout']);
Route::get('/forgot-password', [SuperAdminAuthController::class, 'forgotPasswordView']);
Route::post('/forgot-password', [SuperAdminAuthController::class, 'forgotPassword']);
Route::get('/reset-password', [SuperAdminAuthController::class, 'resetPasswordView']);
Route::post('/reset-password', [SuperAdminAuthController::class, 'resetPassword']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:super_admin']], function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboardView']);
    Route::get('/profile-edit', [ProfileController::class, 'profileEdit']);
    Route::post('/profile-update', [ProfileController::class, 'profileUpdate']);
    Route::post('/password-update', [ProfileController::class, 'changePassword']);

    // company management routes
    Route::get('/company-list', [CompanyController::class, 'showView'])->name('admin.company.view');
    Route::get('/company-list-ajax', [CompanyController::class, 'companyList'])->name('admin.company.list.ajax');
    Route::put('/change-company-status', [CompanyController::class, 'activateDeactivateCompanyAccount'])->name('admin.company-change-status');
    Route::post('/company-delete-account', [CompanyController::class, 'deleteAccount'])->name('admin.company.delete-account');
    Route::get('/company-edit-account', [CompanyController::class, 'editAccountView']);
    Route::post('/company-edit-account', [CompanyController::class, 'updateAccountDetails'])->name('admin.company.edit-account');
    Route::get('/company-detail', [CompanyController::class, 'companyDetailsView'])->name('admin.company-detail.view');

    // company reports
    Route::get('/company-reports', [CompanyReportController::class, 'showView']);
    Route::get('/company-reports-data', [CompanyReportController::class, 'fetchChartsData']);

    // staff    
    Route::get('/staff-list', [CompanyStaffController::class, 'view']);
    Route::get('/staff-list-ajax', [CompanyStaffController::class, 'staffList'])->name('staff.list.ajax');
    Route::put('/change-staff-status', [CompanyStaffController::class, 'activateDeactivateStaffAccount'])->name('admin.staff-change-status');
    Route::post('/staff-delete-account', [CompanyStaffController::class, 'deleteAccount'])->name('admin.staff.delete-account');
    Route::get('/staff-detail', [CompanyStaffController::class, 'staffDetailsView'])->name('admin.staff.view');

    // website management
    Route::get('/pages', [WebsitePageManagementController::class, 'view'])->name('admin.website-management.view');
    Route::post('/pages', [WebsitePageManagementController::class, 'updateContent'])->name('admin.website-management.update');

    Route::any('/subscription-plan-list', function () {
        return view('superAdmin.subscription.planlist');
    })->name('subscription.plan.list');

    Route::any('/subscription', function () {
        return view('superAdmin.subscription.subscription');
    })->name('subscription');

    Route::any('/subscription-logs', function () {
        return view('superAdmin.subscription.logs');
    })->name('subscription.logs');
});
