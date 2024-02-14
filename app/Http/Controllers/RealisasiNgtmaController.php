<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRealisasiRequest;
use App\Http\Requests\UpdateRealisasiRequest;
use App\Services\RealisasiNgtmaService;
use App\Services\SalesService;

class RealisasiNgtmaController extends Controller
{
    public function __construct(private RealisasiNgtmaService $service, private SalesService $salesService)
    {
    }

    public function index()
    {
        $data = $this->service->list();
        $sales = $this->salesService->listAvaible('ngmta');
        return view("pages.realisasi.ngtma", ["js" => url("/js/realisasi/ngtma.js"), "data" => $data, "sales" => $sales]);
    }

    public function detail($id)
    {
        $data = $this->service->singleShow($id);
        $sales = $this->salesService->listAvaibleDetail('ngmta');

        return view("pages.realisasi.update.index", ["js" => url("/js/realisasi/ngtma.js"), "data" => $data, "sales" => $sales]);
    }


    public function listDetail($userId, $salesId)
    {
        $data = $this->service->listDetail($salesId, $userId);

        return response()->json($data);
    }

    public function singleData($id)
    {
        $data = $this->service->singleShow($id);

        return response()->json($data);
    }

    public function create(CreateRealisasiRequest $request)
    {
        $result = $this->service->create($request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }


    public function update($id, UpdateRealisasiRequest $request)
    {
        $result = $this->service->update($id, $request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->service->delete($id);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }
}
