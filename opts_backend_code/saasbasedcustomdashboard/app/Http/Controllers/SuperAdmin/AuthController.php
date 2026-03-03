<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SuperAdminLoginRequest;
use App\Notifications\ForgotPasswordMail;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * render the login view
     */
    public function loginView()
    {
        return view('superAdmin.login');
    }

    /**
     * Handle the login post request
     */
    public function login(SuperAdminLoginRequest $request)
    {
        $data = $request->validated();
        if (
            Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember_me'] ?? false)
        ) {
            return redirect('/admin/dashboard')->with("success", 'Welcome Back ' . auth()->user()->name);
        } else {
            return redirect('/login')->with("error", "Invalid Credentials");
        }
    }

    /**
     * Handle logout action
     */
    public function logout()
    {
        auth()->logout();
        return redirect('/login')->with('success', 'Logged out successfully');
    }

    /**
     * Handle forgot password view
     */
    public function forgotPasswordView()
    {
        return view('superAdmin.forgot-password');
    }

    /**
     * Handle post request for the forgot password
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $data = $request->validated();
        if (isset($data['email']) && !empty($data['email'])) {
            $superAdminDetails = $this->userService->findUserByEmail($data['email']);
            if (isset($superAdminDetails) && $superAdminDetails->type === UserTypeEnum::SUPER_ADMIN->value) {
                $token = generateTokenWithTimeStamp($superAdminDetails->email);
                $this->userService->updateUserDetailsById(
                    $superAdminDetails->id,
                    ['reset_pass_token' => $token['token']]
                );
                $superAdminDetails->notify(new ForgotPasswordMail($token['encryptedToken'], UserTypeEnum::SUPER_ADMIN));
                return back()->with('success', 'Reset password link has been sent to your email-id');
            } else {
                return back()->with("error", 'Email Id is not registered');
            }
        }
    }

    /**
     * Render the reset password view
     */
    public function resetPasswordView(Request $request)
    {
        $token = $request->only('token')['token'] ?? null;
        if ($token === null) {
            return redirect("/login")->with("error", "Invalid url");
        }
        return view('superAdmin.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password post request
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $payload = $request->all();
        $decodedString = explode('|', Crypt::decryptString($payload['pass_token']));
        $token = $decodedString[0];
        $timeStamp = (int)$decodedString[1];
        $email = $decodedString[2] ?? null;
        $user = $this->userService->findUserResetPassToken($email, $token);

        if ((now()->timestamp >= $timeStamp) || $email === null || $user === null) {
            return redirect("/login")->with('error', 'Link expired or invalid link');
        }

        $this->userService->updateUserDetailsById($user->id, [
            'password' => Hash::make($payload['password']),
            'reset_pass_token' => null
        ]);
        return redirect("/login")->with("success", 'Password updated successfully');
    }
}
