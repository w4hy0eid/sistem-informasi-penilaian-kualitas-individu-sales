<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Http\Requests\CreateSalesRequest;
use App\Http\Requests\UpdateSalesRequest;
use App\Models\Sales;
use App\Models\RealisasiNgtma;
use App\Models\RealisasiScaling;
use App\Models\RealisasiSustain;
use Illuminate\Support\Facades\DB;

class SalesService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function create(CreateSalesRequest $request)
    {
        $data_r_ngtma = [];
        $data_r_sustain = [];
        $data_r_scaling = [];

        $result = Sales::create([
            'judul_project' => $request->judul_project,
            'nama_pelanggan' => $request->nama_pelanggan,
            'mitra' => $request->mitra,
            'deal_dibulan' => $request->deal_dibulan,
            'nilai_project' => $request->nilai_project,
            'lama_kontrak' => $request->lama_kontrak,
            'pembayaran_bulanan' => $request->pembayaran_bulanan,
            'type' => $request->type,
            'user_id' => $request->user_id
        ]);

        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        // pembayaran(realisasi)
        $r_ngtma = RealisasiNgtma::where('user_id', $request->user_id)->get()->toArray();
        $r_sustain = RealisasiSustain::where('user_id', $request->user_id)->get()->toArray();
        $r_scaling = RealisasiScaling::where('user_id', $request->user_id)->get()->toArray();

        if (count($r_ngtma) > 0 && $request->type === 'ngtma') {
            foreach ($r_ngtma as $key => $value) {
                array_push($data_r_ngtma, [
                    'month' => $request->deal_dibulan,
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $request->user_id
                ]);
            }

            array_push($data_r_ngtma, [
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => intval($request->user_id)
            ]);

            foreach ($data_r_ngtma as $key => $value) {
                RealisasiNgtma::create([
                    'month' => $value['month'],
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $value['user_id']
                ]);
            }
        } else if (count($r_ngtma) === 0 && $request->type === 'ngtma') {
            RealisasiNgtma::create([
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => $request->user_id
            ]);
        }

        if (count($r_sustain) > 0 && $request->type === 'existing') {
            foreach ($r_sustain as $key => $value) {
                array_push($data_r_sustain, [
                    'month' => $request->deal_dibulan,
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $request->user_id
                ]);
            }

            array_push($data_r_sustain, [
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => $request->user_id
            ]);

            // save bulk
            foreach ($data_r_ngtma as $key => $value) {
                RealisasiSustain::create([
                    'month' => $value['month'],
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $value['user_id']
                ]);
            }
        } else if(count($r_sustain) === 0 && $request->type === 'existing') {
            RealisasiSustain::create([
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => $request->user_id
            ]);
        }

        if (count($r_scaling) > 0 && $request->type === 'new') {
            foreach ($r_ngtma as $key => $value) {
                array_push($data_r_scaling, [
                    'month' => $request->deal_dibulan,
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $request->user_id
                ]);
            }

            array_push($data_r_scaling, [
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => $request->user_id
            ]);

            // save bulk
            foreach ($data_r_ngtma as $key => $value) {
                RealisasiScaling::create([
                    'month' => $value['month'],
                    'value' => $value['value'],
                    'sales_id' => $value['sales_id'],
                    'user_id' => $value['user_id']
                ]);
            }
        } else if(count($r_scaling) === 0 && $request->type === 'new') {
            RealisasiScaling::create([
                'month' => $request->deal_dibulan,
                'value' => $request->pembayaran_bulanan,
                'sales_id' => $result->id,
                'user_id' => $request->user_id
            ]);
        }

        return $this->returnService->Return(NULL, true, "Successfully to create data");
    }

    private function listSales($type)
    {
        $result = DB::table("sales")
            ->leftJoin("r_ngtma", "sales.id", "=", "r_ngtma.sales_id")
            ->leftJoin("r_scaling", "sales.id", "=", "r_scaling.sales_id")
            ->leftJoin("r_sustain", "sales.id", "=", "r_sustain.sales_id")
            ->select("sales.nilai_project", DB::raw("(sales.nilai_project - (COALESCE(SUM(r_ngtma.value), 0) + COALESCE(SUM(r_scaling.value), 0) + COALESCE(SUM(r_sustain.value), 0))) as sisa_pembayaran"), "sales.id", "sales.judul_project", "sales.nama_pelanggan")
            ->where("sales.user_id", "=", session()->get('user_id'))
            ->where('sales.type', '=', $type)
            ->groupBy("sales.id");

        return $result;
    }

    public function listAvaibleDetail($type)
    {
        $result = $this->listSales($type)->get()->toArray();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function listAvaible($type)
    {
        $result = $this->listSales($type)->having(DB::raw("(sales.nilai_project - (COALESCE(SUM(r_ngtma.value), 0) + COALESCE(SUM(r_scaling.value), 0) + COALESCE(SUM(r_sustain.value), 0)))"), ">", 0)->get()->toArray();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function list()
    {
        $result = Sales::whereHas('users')->get();
        return $this->returnService->Return($result, true, "Successfully");
    }

    public function singleShow($idUser)
    {
        $result = Sales::where('id', $idUser)->first();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function update($id, UpdateSalesRequest $request)
    {
        $sustain = Sales::find($id);

        if (!$sustain) {
            return $this->returnService->Return(NULL, false, "Not Found");
        }

        $sustain->user_id = $request->user_id;
        $sustain->judul_project = $request->judul_project;
        $sustain->nama_pelanggan = $request->nama_pelanggan;
        $sustain->mitra = $request->mitra;
        $sustain->deal_dibulan = $request->deal_dibulan;
        $sustain->nilai_project = $request->nilai_project;
        $sustain->lama_kontrak = $request->lama_kontrak;
        $sustain->pembayaran_bulanan = $request->pembayaran_bulanan;
        $sustain->type = $request->type;
        $sustain->user_id = session()->get('user_id');
        $sustain->save();

        return $this->returnService->Return(NULL, true, "Successfully to update data");
    }

    public function delete($id)
    {
        $user = Sales::find($id);
        $user->delete();

        return $this->returnService->Return(NULL, true, "Successfully");
    }
}
