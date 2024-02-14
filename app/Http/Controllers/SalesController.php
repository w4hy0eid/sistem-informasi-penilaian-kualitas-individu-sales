<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalesRequest;
use App\Http\Requests\UpdateSalesRequest;
use App\Services\SalesService;

class SalesController extends Controller
{
    public function __construct(private SalesService $salesService)
    {
    }

    public function index()
    {
        $data = $this->salesService->list();
        return view("pages.sales.index", ["js" => url("/js/sales/index.js"), "data" => $data]);
    }

    public function create(CreateSalesRequest $request)
    {
        $result = $this->salesService->create($request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function singleData($id)
    {
        $data = $this->salesService->singleShow($id);

        return response()->json($data);
    }

    public function update($id, UpdateSalesRequest $request)
    {
        $result = $this->salesService->update($id, $request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->salesService->delete($id);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }
}
