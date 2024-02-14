<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Services\TargetScalingService;

class TargetScalingController extends Controller
{
    public function __construct(private TargetScalingService $targetService)
    {

    }

    public function index() {
        $data = $this->targetService->list();
        return view("pages.target.scaling", ["js" => url("/js/target/scaling.js"), "data" => $data]);
    }

    public function singleData($id) {
        $data = $this->targetService->singleShow($id);

        return response()->json($data);
    }

    public function create(CreateTargetRequest $request)
    {
        $result = $this->targetService->create($request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }


    public function update($id, UpdateTargetRequest $request)
    {
        $result = $this->targetService->update($id, $request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->targetService->delete($id);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }
}
