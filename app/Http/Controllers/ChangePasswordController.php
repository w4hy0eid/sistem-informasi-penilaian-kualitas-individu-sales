<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Services\AuthService;

class ChangePasswordController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function index()
    {
        return view('pages.change-password.index');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->authService->changePassword($request->password, $request->newPassword, $request->confirmNewPassword);

        if (!$result['valid']) {
            return back()->with('error', $result['message']);
        }

        $request->session()->flush();
        return redirect('/view/login');
    }
}
