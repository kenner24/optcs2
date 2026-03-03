<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PermissionEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Notifications\StaffMemberWelcomeEmail;
use App\Services\StaffService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function __construct(
        private StaffService $staffService,
        private UserService $userService,
    ) {
        $this->middleware(['permission:' . PermissionEnum::SUB_ACCOUNT->value]);
    }

    /**
     * @OA\Get (
     *     path="/api/staff-list",
     *     description="Get User Staff List",
     *     tags={"Staff"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *      @OA\Parameter(
     *        name="pageSize",
     *        in="query",
     *        description="",
     *        required=true,
     *     ),
     *      @OA\Parameter(
     *        name="pageIndex",
     *        in="query",
     *        description="",
     *        required=true,
     *     ),
     *     @OA\Response(response="200", description="Get User Staff List", @OA\JsonContent()),
     * )
     */

    public function getStaffList(Request $request)
    {
        $payload = $request->validate([
            "pageSize" => 'required|numeric|integer',
            "pageIndex" => 'required|numeric|integer',
        ]);
        $user = auth()->user();
        $companyId = ($user->type === UserTypeEnum::COMPANY->value) ? $user?->id : $user?->company_id;
        $excludeId = ($user->type !== UserTypeEnum::COMPANY->value) ? $user?->id : null;
        $pageSize = $payload['pageSize'];
        $pageIndex = $payload['pageIndex'];
        $offset = ($pageIndex === 0) ? 0 : $pageSize * $pageIndex;
        $data = $this->staffService->paginatedStaffList($companyId, $offset, $pageSize, $excludeId);
        $totalRecords = $this->staffService->getStaffCount($companyId, $excludeId);

        return $this->sendResponse('Success', [
            "rows" => $data,
            "totalRows" => $totalRecords
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/change-staff-status",
     *     description="Activate or deactivate the staff member account",
     *     tags={"Staff"},
     *     @OA\Response(response="200", description="", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="id",
     *           description="Staff member id",
     *           type="integer",
     *         ),
     *          @OA\Property(
     *           property="activate",
     *           description="Account status",
     *           type="boolean",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     * ===============================================
     * Activate or deactivate the staff member account
     */
    public function activateDeactivateAcc(Request $request)
    {
        try {
            $payload = $request->validate([
                "id" => "required|integer|numeric",
                "activate" => "required|boolean"
            ]);
            $companyId = auth()->user()->id;
            $status = $payload['activate'] ? UserStatusEnum::ACTIVE : UserStatusEnum::IN_ACTIVE;
            $this->staffService->updateStaffStatus($payload['id'], $status, $companyId);
            return $this->sendResponse("Status updated successfully", []);
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/staff-edit",
     *     description="Edit staff member details",
     *     tags={"Staff"},
     *     @OA\Response(response="200", description="", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="id",
     *           description="Staff member id",
     *           type="integer",
     *         ),
     *          @OA\Property(
     *           property="name",
     *           description="Staff member name",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     * ===============================================
     * Edit the staff member details
     */
    public function editStaffDetail(Request $request)
    {
        try {
            $payload = $request->validate([
                "id" => "required|integer|numeric",
                "name" => "required|string|max:255|regex:/^[A-Za-z\s]+$/"
            ]);
            $companyId = auth()->user()->id;
            $this->staffService->updateStaffDetails(
                $payload['id'],
                $companyId,
                [
                    "name" => $payload['name']
                ]
            );
            return $this->sendResponse("Details updated successfully", []);
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/staff-delete",
     *     description="Delete the company staff member",
     *     tags={"Staff"},
     *     @OA\Response(response="200", description="", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="id",
     *           description="",
     *           type="integer",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     * ======================================================
     * Delete the staff member of the company
     * 
     */
    public function staffDelete(Request $request)
    {
        try {
            $companyId = auth()->user()->id;
            $payload = $request->validate([
                'id' => 'required|numeric|integer'
            ]);
            $this->staffService->delete($payload['id']);
            $staffMemberCount = $this->userService->getTotalStaffCount($companyId);
            $this->userService->updateUserDetailsById($companyId, [
                'total_employees' => $staffMemberCount
            ]);
            return $this->sendResponse("Staff member deleted successfully");
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/staff-add",
     *     description="User Staff Add API",
     *     tags={"Staff"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="User Staff Add API", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="name",
     *           description="name of the staff.",
     *           type="string",
     *         ),
     *     @OA\Property(
     *           property="email",
     *           description="password of the staff.",
     *           type="string",
     *         ),
     *        ),
     *     ),
     *   ),
     * )
     */
    public function staffAdd(Request $request)
    {
        try {
            $payload = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'name' => ['required', 'string', 'max:255'],
            ]);

            $companyId = auth()->user()->id;
            $password = Str::random(8);

            $user = $this->staffService->create([
                'name'     => $payload['name'],
                'email'    => $payload['email'],
                'password' => Hash::make($password),
                'type'     => UserTypeEnum::STAFF,
                'status'   => UserStatusEnum::ACTIVE,
                'company_id' => $companyId,
                'email_verified_at' => now()
            ]);

            $user->notify(new StaffMemberWelcomeEmail($password));

            return $this->sendResponse("Staff member created successfully", [], HTTP_CREATED);
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
