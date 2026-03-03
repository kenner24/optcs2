<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{

    public function __construct(
        private CompanyService $companyService
    ) {
    }

    /**
     * @OA\Get (
     *     path="/api/user-profile",
     *     description="Get the user profile details",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="", @OA\JsonContent())
     * )
     *
     * ========================================================================
     * Get the user profile
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(Request $request)
    {
        $user = auth()->user()->load("staffCompanyDetails");
        return $this->sendResponse('Profile fetched successfully', [
            "id" => $user->id,
            "name" =>  $user->name,
            "email"  => $user->email,
            "image" => $user->image,
            "status" => $user->status,
            "company_name" => $user->company_name,
            "username" => $user->username,
            "sales_force_access" => $user->sales_force_access,
            "google_sheet_access" => $user->google_sheet_access,
            "quick_books_access" => $user->quick_books_access,
            "total_employees" => $user->total_employees,
            "domain_sector" => $user->domain_sector,
            "work_email" => $user->work_email,
            "assigned_permissions" => $user->assigned_permissions,
            "type" => $user->type,
            "company_details" => [
                "company_name" => $user?->staffCompanyDetails->company_name ?? null,
                "total_employees" => $user?->staffCompanyDetails?->total_employees ?? null,
                "domain_sector" => $user?->staffCompanyDetails?->domain_sector ?? null,
                "company_id" => $user?->staffCompanyDetails?->id ?? null,
            ]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/user-change-password",
     *     description="Api to change the user password from the company settings page",
     *     tags={"User"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="old_password",
     *           description="Old Password",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="password",
     *           description="Password",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="password_confirmation",
     *           description="Confirm Password",
     *           type="string",
     *         ),
     *        ),
     *     ),
     *   ),
     * )
     */
    public function changePassword(Request $request)
    {
        try {
            $payload = $request->validate([
                "old_password" => ["required", "string"],
                "password" => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                ],
            ]);

            $user = auth()->user();
            $message = null;

            if (Hash::check($payload['old_password'], $user->password)) {
                $hashPwd = Hash::make($payload['password']);
                $this->companyService->updateCompanyDetails($user->id, ['password' => $hashPwd]);
                $user->tokens()->where('id', '<>', $user->currentAccessToken()->id)->delete();
                $message = "Password changed successfully";
            } else {
                $message = "Old Password didn't match";
            }

            return $this->sendResponse($message, []);
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/user-profile-update",
     *     description="User Profile Update API",
     *     tags={"User"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="User Profile Update API", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="name",
     *           description="name",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="username",
     *           description="username",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="company_name",
     *           description="Company Name",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="work_email",
     *           description="Work Email",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="total_employees",
     *           description="Total Employees",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="domain_sector",
     *           description="Domain Sector",
     *           type="string",
     *         ),
     *      ),
     *     ),
     *   ),
     * )
     */
    public function profileUpdate(Request $request)
    {
        $payload = $request->validate([
            "name" => ['required', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            "username" => ['required', 'alpha_num:ascii', 'min:3', 'max:255'],
            "company_name" => ['min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/'],
            "work_email" => ['nullable', 'max:255', 'email:rfc,dns'],
            "total_employees" => ['nullable', 'numeric', 'integer'],
            "domain_sector" => ['nullable', 'min:3', 'max:255', 'string', 'regex:/^[A-Za-z\s]+$/']
        ]);
        try {
            $user = auth()->user();

            $this->companyService->updateCompanyDetails($user->id, [
                "name" =>  $payload['name'] ?? $user->name,
                "username" =>  $payload['username'] ?? $user->username,
                "company_name" =>  $payload['company_name'] ?? $user->company_name,
                "work_email" =>  $payload['work_email'] ?? $user->work_email,
                "total_employees" =>  $payload['total_employees'] ?? $user->total_employees,
                "domain_sector" => $payload['domain_sector'] ?? $user->domain_sector
            ]);
            $userDetails = $this->companyService->getCompanyDetails($user->id);
            return $this->sendResponse("Details updated successfully", $userDetails);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
