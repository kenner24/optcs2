<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $code = $request->query('code');

            if ($code === null) {
                return $this->sendError('Code is required field', HTTP_BAD_REQUEST);
            }

            $decodedString = explode('|', Crypt::decryptString($code));
            $token = $decodedString[0];
            $timeStamp = (int)$decodedString[1];
            $email = $decodedString[2] ?? null;
            $user = User::where('email', $email)->where('email_token', $token)->first();

            if ((now()->timestamp >= $timeStamp) || $email === null || $user === null) {
                return $this->sendError('Verification link expired or invalid link', HTTP_BAD_REQUEST);
            }

            if ($user?->hasVerifiedEmail()) {
                return $this->sendResponse("Email already verified. Please try login", ['verified' => 1], HTTP_OK);
            }

            $user->markEmailAsVerified();
            $user->update(['email_token' => null]);
            return $this->sendResponse("Email verification successful", ['verified' => 1], HTTP_OK);
        } catch (\Exception $th) {
            return $this->sendError('Internal server error', HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
