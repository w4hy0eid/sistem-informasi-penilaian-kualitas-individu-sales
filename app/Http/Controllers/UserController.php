<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        $data = $this->userService->list();
        return view("pages.user.index", ["js" => url("/js/user/index.js"), "data" => $data]);
    }

    public function singleData($id) {
        $data = $this->userService->singleShow($id);

        return response()->json($data);
    }

    public function list() {
        $data = $this->userService->list();

        return response()->json($data);
    }

    public function create(CreateUserRequest $request)
    {
        $result = $this->userService->create($request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }


    public function update($id, UpdateUserRequest $request)
    {
        $result = $this->userService->update($id, $request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->userService->delete($id);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function import()
    {
        $result = $this->userService->importExcel();

        if (!$result['valid']) {
            return back()->with('error', $result['message']);
        }

        return redirect('/view/user');
    }
}
