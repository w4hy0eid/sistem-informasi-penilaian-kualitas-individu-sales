<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRealisasiRequest;
use App\Http\Requests\UpdateRealisasiRequest;
use App\Services\RealisasiSustainService;
use App\Services\SalesService;

class RealisasiSustainController extends Controller
{
    public function __construct(private RealisasiSustainService $rSustainService, private SalesService $salesService)
    {
    }

    public function index()
    {
        $data = $this->rSustainService->list();
        $sales = $this->salesService->listAvaible('existing');

        return view("pages.realisasi.sustain", ["js" => url("/js/realisasi/sustain.js"), "data" => $data, "sales" => $sales]);
    }

    public function detail($id)
    {
        $data = $this->rSustainService->singleShow($id);
        $sales = $this->salesService->listAvaibleDetail('existing');

        return view("pages.realisasi.update.index", ["js" => url("/js/realisasi/sustain.js"), "data" => $data, "sales" => $sales]);
    }

    public function listDetail($userId, $salesId)
    {
        $data = $this->rSustainService->listDetail($salesId, $userId);

        return response()->json($data);
    }

    public function singleData($id)
    {
        $data = $this->rSustainService->singleShow($id);

        return response()->json($data);
    }

    public function create(CreateRealisasiRequest $request)
    {
        $result = $this->rSustainService->create($request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }


    public function update($id, UpdateRealisasiRequest $request)
    {
        $result = $this->rSustainService->update($id, $request);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->rSustainService->delete($id);

        if (!$result['valid']) {
            return response()->json($result);
        }

        return response()->json($result);
    }
}
