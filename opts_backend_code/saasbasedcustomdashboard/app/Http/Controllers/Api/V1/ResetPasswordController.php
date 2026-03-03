<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     description="Reset password of the user",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Login User", @OA\JsonContent()),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="password",
     *           description="",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="password_confirmation",
     *           description="",
     *           type="string",
     *         ),
     *          @OA\Property(
     *           property="token",
     *           description="",
     *           type="string",
     *         ),
     *       ),
     *     ),
     *   ),
     * )
     */
    public function resetPassword(Request $request)
    {
        $payload = $request->validate([
            'password'  => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            "token" => ['required', 'string']
        ]);

        $decodedString = explode('|', Crypt::decryptString($payload['token']));
        $token = $decodedString[0];
        $timeStamp = (int)$decodedString[1];
        $email = $decodedString[2] ?? null;
        $user = $this->userService->findUserResetPassToken($email, $token);

        if ((now()->timestamp >= $timeStamp) || $email === null || $user === null) {
            return $this->sendError('Link expired or invalid link');
        }

        $this->userService->updateUserDetailsById($user->id, [
            'password' => Hash::make($payload['password']),
            'reset_pass_token' => null
        ]);
        
        return $this->sendResponse('Password updated successfully', []);
    }
}
