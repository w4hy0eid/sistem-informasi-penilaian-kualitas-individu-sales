<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportTargetRequest;
use App\Http\Requests\GenerateRequest;
use App\Services\ExportService;

class ExportController extends Controller
{
    public function __construct(private ExportService $service)
    {
    }

    public function index()
    {
        return view('pages.generate.index');
    }

    public function ExportAll(GenerateRequest $request)
    {
        $validated = $request->validated();

        return $this->service->exportReportExcel($validated['month'], $validated['year']);
    }

    public function exportTarget(ExportTargetRequest $request) {
        $validated = $request->validated();
        return $this->service->exportTarget($validated['type'], $validated['tr']);
    }

    public function exportRealisasi(ExportTargetRequest $request) {
        $validated = $request->validated();
        return $this->service->exportRealisasi($validated['type'], $validated['tr']);
    }
}
