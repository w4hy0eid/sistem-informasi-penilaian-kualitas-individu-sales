<?php

namespace App\Services;

use App\Models\Sales;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Helpers\ReturnService;
use App\Models\TargetNgtma;
use App\Models\TargetScaling;
use App\Models\TargetSustain;
use App\Models\RealisasiNgtma;
use App\Models\RealisasiScaling;
use App\Models\RealisasiSustain;

class ImportService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function importTarget(string $type)
    {
        // upload file xls
        $target = basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $target);

        // beri permisi agar file xls dapat di baca
        chmod($_FILES['file']['name'], 0777);

        // mengambil isi file xls
        $reader = new Xlsx();
        // menghitung jumlah baris data yang ada
        $spreadsheet = $reader->load($_FILES['file']['name']);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $numRow = 1;
        $saveData = [];
        $errorFound = [];


        foreach ($sheet as $key => $value) {
            // print_r($value);
            $userId = $value['A'];

            if ($numRow > 1) {

                $data = User::where('id', intval($userId))->get()->toArray();

                if (count($data) === 0) {
                    // dd($data->toArray());
                    array_push($errorFound, "user_id is not found $userId");
                    break;
                }

                if (!is_numeric($value['B']) || !is_numeric($value['C']) || !is_numeric($value['D']) || !is_numeric($value['E']) || !is_numeric($value['F']) || !is_numeric($value['G']) || !is_numeric($value['H']) || !is_numeric($value['I']) || !is_numeric($value['J']) || !is_numeric($value['K']) || !is_numeric($value['L']) || !is_numeric($value['M']) || !is_numeric($value['N'])) {
                    array_push($errorFound, "must be number");
                    break;
                }

                array_push($saveData, [
                    'user_id' => $userId,
                    'value_januari' => $value['B'],
                    'value_febuari' => $value['C'],
                    'value_maret' => $value['D'],
                    'value_april' => $value['E'],
                    'value_mei' => $value['F'],
                    'value_juni' => $value['G'],
                    'value_juli' => $value['H'],
                    'value_agustus' => $value['I'],
                    'value_september' => $value['J'],
                    'value_oktober' => $value['K'],
                    'value_november' => $value['L'],
                    'value_desember' => $value['M'],
                    'value_year' => $value['N'],
                ]);
            }
            $numRow++;
        }

        // hapus kembali file .xls yang di upload tadi
        unlink($_FILES['file']['name']);

        if (count($errorFound) === 0) {
            if (count($saveData) === 0) {
                return $this->returnService->Return(NULL, false, "Error 1");
            }
        } else {
            return $this->returnService->Return(NULL, false, $errorFound[0]);
        }

        $result = NULL;
        if ($type === "ngtma") {
            $result = TargetNgtma::insert($saveData);
        } else if ($type === "scaling") {
            $result = TargetScaling::insert($saveData);
        } else if ($type === "sustain") {
            $result = TargetSustain::insert($saveData);
        }

        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        return $this->returnService->Return(NULL, true, "Successfully to import");
    }

    public function importExcel()
    {
        $data_r_ngtma = [];
        $data_r_sustain = [];
        $data_r_scaling = [];

        // upload file xls
        $target = basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $target);

        // beri permisi agar file xls dapat di baca
        chmod($_FILES['file']['name'], 0777);

        // mengambil isi file xls
        $reader = new Xlsx();
        // menghitung jumlah baris data yang ada
        $spreadsheet = $reader->load($_FILES['file']['name']);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $statusInsert = 1;
        $numRow = 1;
        $saveData = [];
        $errorFound = [];

        foreach ($sheet as $key => $value) {
            $userId = $value['C'];

            // mapping data
            if ($numRow > 1) {
                $user = User::where('id', $userId)->first();

                if ($user === NULL) {
                    array_push($errorFound, "user_id is not found");
                    break;
                }

                // check if id seri & master pd is is empty
                if ($userId == "") {
                    array_push($errorFound, "user_id is required");
                    break;
                }

                if ($value['I'] == "") {
                    array_push($errorFound, "type is required");
                    break;
                }

                $lamaKontrak = preg_replace('/[^0-9]/', '', $value['G']);

                if (!is_numeric($value['E'])) {
                    array_push($errorFound, "BULAN DEAL must be number");
                    break;
                }

                array_push($saveData, [
                    'judul_project' => $value['A'],
                    'nama_pelanggan' => $value['B'],
                    'user_id' => $userId,
                    'mitra' => $value['D'],
                    'deal_dibulan' => $value['E'],
                    'nilai_project' => (int)str_replace(',', '', $value['F']),
                    'lama_kontrak' => $lamaKontrak,
                    'pembayaran_bulanan' => (int)str_replace(',', '', $value['H']),
                    'type' => $value['I'],
                ]);
            }
            $numRow++;
        }

        // hapus kembali file .xls yang di upload tadi
        unlink($_FILES['file']['name']);

        // dd($saveData, $errorFound);

        if (count($errorFound) === 0) {
            if (count($saveData) === 0) {
                return $this->returnService->Return(NULL, false, "Error 1");
            }
        } else {
            return $this->returnService->Return(NULL, false, $errorFound[0]);
        }

        foreach ($saveData as $key => $value) {
            $result = Sales::create([
                'judul_project' => $value['judul_project'],
                'nama_pelanggan' => $value['nama_pelanggan'],
                'user_id' => $value['user_id'],
                'mitra' => $value['mitra'],
                'deal_dibulan' => $value['deal_dibulan'],
                'nilai_project' => $value['nilai_project'],
                'lama_kontrak' => $value['lama_kontrak'],
                'pembayaran_bulanan' => $value['pembayaran_bulanan'],
                'type' => $value['type'],
            ]);

            // pembayaran(realisasi)
            $r_ngtma = RealisasiNgtma::where('user_id', $value['user_id'])->get()->toArray();
            $r_sustain = RealisasiSustain::where('user_id', $value['user_id'])->get()->toArray();
            $r_scaling = RealisasiScaling::where('user_id', $value['user_id'])->get()->toArray();

            // kondisi insert pertama kali
            if ($statusInsert === 1) {
                if (count($r_ngtma) > 0 && $value['type'] === 'ngtma') {
                    foreach ($r_ngtma as $key => $v) {
                        array_push($data_r_ngtma, [
                            'month' => $value['deal_dibulan'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $value['user_id']
                        ]);
                    }

                    array_push($data_r_ngtma, [
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);

                    // save bulk
                    foreach ($data_r_ngtma as $key => $v) {
                        RealisasiNgtma::create([
                            'month' => $v['month'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $v['user_id']
                        ]);
                    }
                } else if(count($r_ngtma) === 0 && $value['type'] === 'ngtma') {
                    RealisasiNgtma::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }

                if (count($r_sustain) > 0 && $value['type'] === 'existing') {
                    foreach ($r_sustain as $key => $v) {
                        array_push($data_r_sustain, [
                            'month' => $value['deal_dibulan'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $value['user_id']
                        ]);
                    }

                    array_push($data_r_sustain, [
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);

                    // save bulk
                    foreach ($data_r_ngtma as $key => $v) {
                        RealisasiSustain::create([
                            'month' => $v['month'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $v['user_id']
                        ]);
                    }
                } else if(count($r_sustain) === 0 && $value['type'] === 'existing') {
                    RealisasiSustain::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }

                if (count($r_scaling) > 0 && $value['type'] === 'new') {
                    foreach ($r_ngtma as $key => $v) {
                        array_push($data_r_scaling, [
                            'month' => $value['deal_dibulan'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $value['user_id']
                        ]);
                    }

                    array_push($data_r_scaling, [
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);

                    // save bulk
                    foreach ($data_r_ngtma as $key => $v) {
                        RealisasiScaling::create([
                            'month' => $v['month'],
                            'value' => $v['value'],
                            'sales_id' => $v['sales_id'],
                            'user_id' => $v['user_id']
                        ]);
                    }
                } else if(count($r_scaling) === 0 && $value['type'] === 'new') {
                    RealisasiScaling::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }

                $data_r_ngtma = [];
                $data_r_scaling = [];
                $data_r_sustain = [];
                $statusInsert++;
            } else if ($statusInsert > 1) {
                if ($value['type'] === 'ngtma') {
                    RealisasiNgtma::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }

                if ($value['type'] === 'existing') {
                    RealisasiSustain::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }


                if($value['type'] === 'new') {
                    RealisasiScaling::create([
                        'month' => $value['deal_dibulan'],
                        'value' => $value['pembayaran_bulanan'],
                        'sales_id' => $result->id,
                        'user_id' => $value['user_id']
                    ]);
                }

            }
        }


        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        return $this->returnService->Return(NULL, true, "Successfully to import");
    }
}
