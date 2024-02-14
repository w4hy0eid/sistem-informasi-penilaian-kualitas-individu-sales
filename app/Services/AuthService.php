<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function doLogin(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->returnService->Return(NULL, false, "User not found");
        }

        if (!Hash::check($password, $user->password)) {
            return $this->returnService->Return(NULL, false, "Password is not match");
        }

        session(['is_login' => true, 'user_id' => $user->id, 'role' => $user->role, 'name' => $user->name, 'tr' => $user->tr]);

        return $this->returnService->Return(NULL, true, "Successfully to login");
    }

    public function changePassword(string $password, string $newPassword, string $confirmNewPassword)
    {
        $user = User::find(session()->get('user_id'));

        if (!$user) {
            return $this->returnService->Return(NULL, false, "User not found");
        }

        if (!Hash::check($password, $user->password)) {
            return $this->returnService->Return(NULL, false, "Current password is not match");
        }

        if (strcmp($newPassword, $confirmNewPassword) !== 0) {
            return $this->returnService->Return(NULL, false, "New password with confirm not match");
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return $this->returnService->Return(NULL, true, "Successfully to change password");

    }
}
