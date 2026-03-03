<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * @OA\Post (
     *     path="/api/mail-verification-notification",
     *     description="Resend the email verification mail to the user",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="Company registered", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *             ),
     *           ),
     *         ),
     *      ),
     * )
     * ====================================================================================
     * Send a email verify email to the user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $payload = $request->validate([
            'email' => ['bail', 'required', 'string', 'email'],
        ]);

        $email = $payload['email'];

        $user = User::where('email', $email)->first();

        if ($user === null) {
            return $this->sendError("Email not found", HTTP_NOT_FOUND);
        }

        if ($user->email_verified_at !== null) {
            return $this->sendError("Email already verified.", HTTP_BAD_REQUEST);
        }

        $token = generateTokenWithTimeStamp($user->email);
        $user->update(['email_token' => $token['token']]);
        $user->notify(new EmailVerification($token['encryptedToken']));
        return $this->sendResponse("Email verification instruction sent", [], HTTP_OK);
    }
}
