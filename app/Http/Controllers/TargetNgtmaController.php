<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Services\TargetNgtmaService;

class TargetNgtmaController extends Controller
{
    public function __construct(private TargetNgtmaService $targetService)
    {

    }

    public function index() {
        $data = $this->targetService->list();
        return view("pages.target.ngtma", ["js" => url("/js/target/ngtma.js"), "data" => $data]);
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
