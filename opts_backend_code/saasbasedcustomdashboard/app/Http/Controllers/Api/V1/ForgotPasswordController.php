<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Notifications\ForgotPasswordMail;
use App\Services\UserService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     description="Send forgot password email to the user",
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
     *       ),
     *     ),
     *   ),
     * )
     */
    public function sendForgotPasswordEmail(ForgotPasswordRequest $request)
    {
        $data = $request->validated();
        if (isset($data['email']) && !empty($data['email'])) {
            $userDetails = $this->userService->findUserByEmail($data['email']);
            if (
                isset($userDetails) &&
                (in_array($userDetails->type, [UserTypeEnum::COMPANY->value, UserTypeEnum::STAFF->value]))
            ) {
                $token = generateTokenWithTimeStamp($userDetails->email);
                $this->userService->updateUserDetailsById(
                    $userDetails->id,
                    ['reset_pass_token' => $token['token']]
                );
                $userDetails->notify(new ForgotPasswordMail($token['encryptedToken'], UserTypeEnum::COMPANY));
                return $this->sendResponse('Reset password link has been sent to your email-id', []);
            } else {
                return $this->sendError('Email Id is not registered', HTTP_NOT_FOUND);
            }
        }
    }
}
