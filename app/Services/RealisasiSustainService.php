<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Http\Requests\CreateRealisasiRequest;
use App\Http\Requests\UpdateRealisasiRequest;
use App\Models\RealisasiSustain;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;

class RealisasiSustainService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    private function salesAvaible($salesId)
    {
        $result = DB::table("sales")
            ->leftJoin("r_ngtma", "sales.id", "=", "r_ngtma.sales_id")
            ->leftJoin("r_scaling", "sales.id", "=", "r_scaling.sales_id")
            ->leftJoin("r_sustain", "sales.id", "=", "r_sustain.sales_id")
            ->select(DB::raw("(sales.nilai_project - (COALESCE(SUM(r_ngtma.value), 0) + COALESCE(SUM(r_scaling.value), 0) + COALESCE(SUM(r_sustain.value), 0))) as sisa_pembayaran"), "sales.id", "sales.judul_project", "sales.nama_pelanggan")
            ->where("sales.id", "=", $salesId)
            ->groupBy("sales.id")->first();

        return $result;
    }

    public function create(CreateRealisasiRequest $request)
    {
        // total by sales_id
        $sumRealisasi = $this->salesAvaible($request->sales_id);

        $sales = Sales::where("id", $request->sales_id)->first();

        if ($sales === NULL) {
            return $this->returnService->Return(NULL, false, "Error 1");
        }

        $sisaPembayaran = ($sumRealisasi->sisa_pembayaran) - $request->value;

        if ($sisaPembayaran < 0) {
            return $this->returnService->Return(NULL, false, "Error 2");
        }

        if ($request->value > intval($sales->pembayaran_bulanan)) {
            return $this->returnService->Return(NULL, false, "Error 3");
        }

        $result = RealisasiSustain::create([
            'month' => $request->month,
            'value' => $request->value,
            'sales_id' => $request->sales_id,
            'user_id' => session()->get('user_id')
        ]);


        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        return $this->returnService->Return(NULL, true, "Successfully to create data");
    }

    public function update($id, UpdateRealisasiRequest $request)
    {
        $sustain = RealisasiSustain::find($id);

        if (!$sustain) {
            return $this->returnService->Return(NULL, false, "Not Found");
        }

        $sumRealisasi = $this->salesAvaible($request->sales_id);

        $sales = Sales::where("id", $request->sales_id)->first();

        if ($sales === NULL) {
            return $this->returnService->Return(NULL, false, "Error 1");
        }

        $sisaPembayaran = ($sumRealisasi->sisa_pembayaran + $sustain->value) - $request->value;

        if ($sisaPembayaran < 0) {
            return $this->returnService->Return(NULL, false, "Error 2");
        }

        if ($request->value > intval($sales->pembayaran_bulanan)) {
            return $this->returnService->Return(NULL, false, "Error 3");
        }

        $sustain->month = $request->month;
        $sustain->value = $request->value;
        $sustain->user_id = session()->get('user_id');
        $sustain->sales_id = $request->sales_id;
        $sustain->save();

        return $this->returnService->Return(NULL, true, "Successfully to update data");
    }

    public function listDetail($salesId, $userId)
    {
        $result = RealisasiSustain::where('user_id', $userId)->where("sales_id", $salesId)->get();

        return $this->returnService->Return($result, true, "Successfully");
    }

    private function partTr()
    {
        $role = session()->get('role');
        $newDataTreg = [];
        $resultTreg = DB::table("r_sustain")
            ->whereYear("r_sustain.created_at", "=", Date("Y"))
            ->leftJoin("users", "r_sustain.user_id", "=", "users.id")
            ->select("users.id as user_id", "users.name", "users.nik", "users.level", "users.tr", "users.segmen", "r_sustain.month", DB::raw("COALESCE(SUM(r_sustain.value), 0) as total_value"), "r_sustain.sales_id")
            ->where("users.tr", "like", "%TR%");

        if ($role != 'admin') {
            $resultTreg->where("users.id", session()->get('user_id'));
        }

        $get = $resultTreg->groupBy(["r_sustain.month", "users.id", "r_sustain.sales_id"])
            ->get()->toArray();

        for ($i = 0; $i < count($get); $i++) {
            $data = $get[$i];
            $newArray = [];

            $newArray['user_id'] = $data->user_id;
            $newArray['name'] = $data->name;
            $newArray['nik'] = $data->nik;
            $newArray['level'] = $data->level;
            $newArray['tr'] = $data->tr;
            $newArray['segmen'] = $data->segmen;
            $newArray['sales_id'] = $data->sales_id;

            for ($idx = 1; $idx <= 12; $idx++) {
                if ($idx === $data->month) {
                    $newArray["value_" . strtolower(getMonthName($idx))] = $data->total_value;
                } else {
                    $newArray["value_" . strtolower(getMonthName($idx))] = 0;
                }
            }

            array_push($newDataTreg, $newArray);
        }

        return $newDataTreg;
    }

    private function partDbs()
    {
        $role = session()->get('role');
        $newDataDbs = [];
        $resultDbs = DB::table("r_sustain")
            ->whereYear("r_sustain.created_at", "=", Date("Y"))
            ->leftJoin("users", "r_sustain.user_id", "=", "users.id")
            ->select("users.id as user_id", "users.name", "users.nik", "users.level", "users.tr", "users.segmen", "r_sustain.month", DB::raw("COALESCE(SUM(r_sustain.value), 0) as total_value"), "r_sustain.sales_id")
            ->where("users.tr", "like", "%DBS%");

        if ($role != 'admin') {
            $resultDbs->where("users.id", session()->get('user_id'));
        }

        $get = $resultDbs->groupBy(["r_sustain.month", "users.id", "r_sustain.sales_id"])
            ->get()->toArray();

        for ($i = 0; $i < count($get); $i++) {
            $data = $get[$i];
            $newArray = [];

            $newArray['user_id'] = $data->user_id;
            $newArray['name'] = $data->name;
            $newArray['nik'] = $data->nik;
            $newArray['level'] = $data->level;
            $newArray['tr'] = $data->tr;
            $newArray['segmen'] = $data->segmen;
            $newArray['sales_id'] = $data->sales_id;

            for ($idx = 1; $idx <= 12; $idx++) {
                if ($idx === $data->month) {
                    $newArray["value_" . strtolower(getMonthName($idx))] = $data->total_value;
                } else {
                    $newArray["value_" . strtolower(getMonthName($idx))] = 0;
                }
            }

            array_push($newDataDbs, $newArray);
        }

        return $newDataDbs;
    }

    public function list()
    {
        $tr = session()->get('tr');
        $role = session()->get('role');
        $newDataDbs = [];
        $newDataTreg = [];

        if ($role === 'admin') {
            $newDataDbs = $this->partDbs();
            $newDataTreg = $this->partTr();
        } else {
            if (str_contains($tr, 'DBS')) {
                $newDataDbs = $this->partDbs();
            } else {
                $newDataTreg = $this->partTr();
            }
        }

        $map = [
            "dbs" =>  $newDataDbs,
            "treg" => $newDataTreg,
        ];

        return $this->returnService->Return($map, true, "Successfully");
    }

    public function singleShow($idUser)
    {
        $result = RealisasiSustain::where('id', $idUser)->first();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function delete($id)
    {
        $user = RealisasiSustain::find($id);
        $user->delete();

        return $this->returnService->Return(NULL, true, "Successfully");
    }
}
