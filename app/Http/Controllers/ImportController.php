<?php

namespace App\Http\Controllers;

use App\Services\ImportService;

class ImportController extends Controller
{
    public function __construct(private ImportService $service)
    {
    }

    public function importTarget($type)
    {
        $result = $this->service->importTarget($type);

        if (!$result['valid']) {
            return back()->with('error', $result['message']);
        }

        return redirect("/view/target-$type");
    }


    public function importExcel()
    {
        $result = $this->service->importExcel();

        if (!$result['valid']) {
            return back()->with('error', $result['message']);
        }

        return redirect('/view/sales');
    }
}
