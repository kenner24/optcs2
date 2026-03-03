<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginApiRequest;
use App\Http\Requests\Api\V1\RegisterApiRequest;
use App\Http\Requests\Api\V1\SocialLogin;
use App\Http\Requests\Api\V1\SocialRegister;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Services\CompanyService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private CompanyService $companyService,
    ) {
    }
    /**
     * @OA\Post (
     *     path="/api/register",
     *     description="Register the company on the platform",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Company registered", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="company_name",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="work_email",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="total_employees",
     *                 type="number",
     *             ),
     *             @OA\Property(
     *                 property="domain_sector",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                 property="username",
     *                 type="string",
     *             ),
     *           ),
     *         ),
     *      ),
     * )
     * ====================================================================================
     * Company Registration
     * @param  \App\Http\Requests\Api\V1\RegisterApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterApiRequest $request)
    {
        try {
            $validatedRequest = $request->validated();
            $token = generateTokenWithTimeStamp($validatedRequest['email']);
            $validatedRequest['password'] = Hash::make($validatedRequest['password']);
            $validatedRequest['email_token'] = $token['token'];
            $validatedRequest['status'] = UserStatusEnum::ACTIVE;
            $validatedRequest['type'] = UserTypeEnum::COMPANY;
            $validatedRequest['company_name'] = $validatedRequest['name'] . " Company";
            $validatedRequest['work_email'] = $validatedRequest["email"];
            $validatedRequest['total_employees'] = 0;
            $validatedRequest['image'] = asset('/dummy_images/dummy.jpeg');

            $user = User::create($validatedRequest);
            $user->assignRole(UserTypeEnum::COMPANY->value);
            $user->notify(new EmailVerification($token['encryptedToken']));
            return $this->sendResponse('Registered successfully', [], HTTP_CREATED);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR, ['error' => $th]);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Login User",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Login User", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="email",
     *           description="Email address of the new user.",
     *           type="string",
     *         ),
     *     @OA\Property(
     *           property="password",
     *           description="password of the new user.",
     *           type="string",
     *         ),
     *      @OA\Property(
     *           property="device_name",
     *           description="Enter device name eg. iphone, android, firefox, chrome",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     */
    public function loginUser(LoginApiRequest $request)
    {
        try {
            $user = $this->userService->findUserByEmail($request->email);

            if (
                !$user ||
                !Hash::check($request->password, $user->password) ||
                !$user->hasRole([UserTypeEnum::COMPANY->value, UserTypeEnum::STAFF->value])
            ) {
                return $this->sendError("Invalid Credentials", HTTP_UNAUTHORIZED);
            }

            if ($user->status === UserStatusEnum::IN_ACTIVE) {
                return $this->sendError('Account deactivated, consult your admin.', HTTP_UNAUTHORIZED);
            }

            if ($user->email_verified_at === null) {
                return $this->sendError('User not verified, please verify.', HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken($request->device_name)->plainTextToken;
            return $this->sendResponse('Login Successful', ['user' => $user, 'token' => $token]);
        } catch (\Exception $th) {
            return $this->sendError('Internal Server Error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Post (
     *     path="/api/user-logout",
     *     description="Logout API",
     *     tags={"Auth"},
     *     security={
     *           {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="Logout API", @OA\JsonContent()),
     * )
     */

    public function userLogout(Request $request)
    {
        $response = [];
        $request->user()->currentAccessToken()->delete();
        $response['message'] = 'Logout successfully.';

        return $this->sendResponse('Logout successfully.', []);
    }


    /**
     * @OA\Post(    
     *     path="/api/social-login",
     *     description="Google Login User",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Google Login User", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="provider_name",
     *           description="Enter provider name",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="provider_id",
     *           description="Enter provider id",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="email",
     *           description="Enter email",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="device_name",
     *           description="Enter device name eg. iphone, android, firefox, chrome",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     */
    public function userSocialLogin(SocialLogin $request)
    {
        try {
            $payload = $request->validated();
            $user = $this->companyService->findSocialUser($payload['provider_id'], $payload['email']);
            if (isset($user)) {
                $token = $user->createToken($payload['device_name'])->plainTextToken;
                return $this->sendResponse("Login successful", ["token" => $token, 'user' => $user]);
            } else {
                return $this->sendError("User Not Registered");
            }
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(    
     *     path="/api/register-social-account",
     *     description="Google Register User",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Google Register User", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="provider_name",
     *           description="Enter provider name",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="provider_id",
     *           description="Enter provider id",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="first_name",
     *           description="Enter First Name",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="last_name",
     *           description="Enter Last Name",
     *           type="string",
     *         ),
     *         @OA\Property(
     *           property="image",
     *           description="image url",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="email",
     *           description="Enter email address",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="device_name",
     *           description="Enter device name eg. iphone, android, firefox, chrome",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     */
    public function userSocialRegister(SocialRegister $request)
    {
        try {
            $validatedRequest = $request->validated();
            $emailArr = explode('@', $validatedRequest['email']);
            $username =  $emailArr[0] . time();
            $password = Hash::make(time() . Str::random(20));

            $user = $this->companyService->createCompanyAccount([
                "name" => $validatedRequest["first_name"] . $validatedRequest["last_name"],
                "email" => $validatedRequest["email"],
                "provider_name" => $validatedRequest["provider_name"],
                "provider_id" => $validatedRequest["provider_id"],
                "password" => $password,
                'status' => UserStatusEnum::ACTIVE,
                'type' => UserTypeEnum::COMPANY,
                'company_name' => $validatedRequest["first_name"] . $validatedRequest["last_name"] . " Company",
                "work_email" => $validatedRequest["email"],
                "total_employees" => 0,
                'username' => $username,
                "email_verified_at" => now(),
                "image" => $validatedRequest['image'] ?? asset('/dummy_images/dummy.jpeg'),
            ]);

            $user->assignRole(UserTypeEnum::COMPANY->value);
            $token = $user->createToken($validatedRequest['device_name'])->plainTextToken;

            return $this->sendResponse("Registration successful", ["token" => $token, "user" => $user], HTTP_CREATED);
        } catch (ValidationException $th) {
            return $this->sendError($th->getMessage(), HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $th) {
            return $this->sendError("Internal server error", HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
