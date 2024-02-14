<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(private AuthService $authService, private UserService $userService)
    {
    }

    public function ViewDashboard() {
        $a = $this->userService->totalUser();
        $b = $this->userService->totalPembayaran();

        return view("pages.dashboard.dashboard", [
            "totalPembayaran" => $b,
            "totalUser" => $a
        ]);
    }

    public function ViewLogin()
    {
        return view("pages/auth/login");
    }

    public function Login(AuthLoginRequest $request)
    {
        $validated = $request->validated();
        $result = $this->authService->doLogin($validated['email'], $validated['password']);

        if (!$result['valid']) {
            return back()->with('error', $result['message']);
        }

        return redirect('/view/dashboard');
    }

    public function Logout(Request $request) {
        $request->session()->flush();
        return redirect('/view/login');
    }
}
