<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;


class ProfileController extends Controller
{

    public function __construct(
        private UserService $userService
    ) {
    }

    public function profileEdit(Request $request)
    {
        $admin = auth()->user();
        return view('superAdmin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $payload = $request->validate([
            'image' => [
                File::types(['jpeg', 'jpg', 'png'])
                    ->max(5 * 1024)
            ],
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[A-Za-z\s]+$/']
        ]);

        $user = auth()->user();
        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('admin/uploads'), $imageName);

            $oldImage = $user->image;
            if (!empty($oldImage)) {
                $filePath = public_path('admin/uploads/' . $oldImage);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $this->userService->updateUserDetailsById($user->id, [
            'name' => $payload['name'],
            'image' => '/admin/uploads/' . $imageName ?? $user->image
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
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
            'old_password' => ['required']
        ]);

        $user = auth()->user();
        if (Hash::check($payload['old_password'], $user->password)) {
            $hashPwd = Hash::make($payload['password']);
            $this->userService->updateUserDetailsById($user->id, ['password' => $hashPwd]);
            return back()->with('success', 'Password updated successfully');
        } else {
            return back()->with('error', 'Old password did not match');
        }
    }
}
