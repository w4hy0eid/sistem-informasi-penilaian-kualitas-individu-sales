<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\TargetNgtma;
use App\Models\TargetScaling;
use App\Models\TargetSustain;
use App\Repositories\RealisasiNgtmaRepository;
use App\Repositories\RealisasiSustainRepository;
use App\Repositories\RealisasiScalingRepository;
use Illuminate\Database\Eloquent\Builder;

class ExportService
{
    public function __construct(private RealisasiNgtmaRepository $realisasiNgtmaRepository, private RealisasiSustainRepository $realisasiSustainRepository, private RealisasiScalingRepository $realisasiScalingRepository)
    {
    }

    private function mappingMonthDataTarget($data, $setMonth)
    {
        $save = [];

        if (count($data) > 0) {
            $dataTaget = $data;
            $total_target_ngtma = 0;
            for ($i = 0; $i < count($dataTaget); $i++) {
                $keys = array_keys($dataTaget[$i]);
                for ($idx = 0; $idx < count($keys); $idx++) {
                    if ($idx > 0) {
                        if (str_contains($keys[$idx], strtolower(getMonthName($idx)))) {
                            if ($idx === (int)$setMonth) {
                                $total_target_ngtma += $dataTaget[$i][$keys[$idx]];
                                break;
                            } else {
                                $total_target_ngtma += $dataTaget[$i][$keys[$idx]];
                            }
                        }
                    }
                }
            }
            $save['total'] = $total_target_ngtma;
        } else {
            $save['total'] = 0;
        }
        return $save;
    }

    private function mappingMonthData($data, $setMonth)
    {

        $month = [];
        $save = [];

        for ($i = 1; $i <= $setMonth; $i++) {
            array_push($month, $i);
        }

        if (count($data) >= 0) {
            $data_target_ngtmas = $data;
            $total_target_ngtma = 0;

            for ($i = 0; $i < count($month); $i++) {
                if (array_search($month[$i], array_column($data, 'month')) !== FALSE) {
                    $key = array_search($month[$i], array_column($data, 'month'));
                    $total_target_ngtma += $data_target_ngtmas[$key]['total_val'];
                }
            }

            $save['total'] = $total_target_ngtma;
        }

        return $save;
    }

    private function setNameHeader($title)
    {
        switch ($title) {
            case 'TARGET_SCALING':
                return 'TARGET SCALING';
                break;

            case 'TARGET_NGTMAS':
                return 'TARGET NGTMA';
                break;

            case 'REALISASI_POTS':
                return 'REALISASI POTS';
                break;

            case 'REALISASI_CONNECTIVITY':
                return 'REALISASI CONNECTIVITY';
                break;

            case 'REALISASI_DIGITAL':
                return 'REALISASI DIGITAL';
                break;

            case 'REALISASI_EKSIS':
                return 'REALISASI EKSIS';
                break;

            case 'REALISASI_WIFI':
                return 'REALISASI WIFI';
                break;

            case 'REALISASI_NGTMA':
                return 'REALISASI NGTMA';
                break;

            case 'TARGET_SUSTAIN':
                return 'TARGET SUSTAIN';
                break;

            case 'REALISASI_SUSTAIN':
                return 'REALISASI SUSTAIN';
                break;

            case 'TOTAL_REALISASI_SCALING':
                return 'TOTAL REALISASI SCALING';
                break;

            default:
                return 'UNKNOWN';
                break;
        }
    }

    private function setNameHeaderTotal($title, $month)
    {
        switch ($title) {
            case 'TARGET_SCALING':
                return 'TARGET SCALING YTD ' . getMonthName($month);
                break;

            case 'TARGET_NGTMAS':
                return 'TARGET SCALING YTD ' . getMonthName($month);
                break;

            case 'REALISASI_POTS':
                return 'REALISASI POTS YTD ' . getMonthName($month);
                break;

            case 'REALISASI_CONNECTIVITY':
                return 'REALISASI CONNECTIVITY YTD ' . getMonthName($month);
                break;

            case 'REALISASI_DIGITAL':
                return 'REALISASI DIGITAL YTD ' . getMonthName($month);
                break;

            case 'REALISASI_EKSIS':
                return 'REALISASI EKSIS YTD ' . getMonthName($month);
                break;

            case 'REALISASI_WIFI':
                return 'REALISASI WIFI YTD ' . getMonthName($month);
                break;

            case 'REALISASI_NGTMA':
                return 'REALISASI NGTMA YTD ' . getMonthName($month);
                break;

            case 'TARGET_SUSTAIN':
                return 'TARGET SUSTAIN YTD ' . getMonthName($month);
                break;

            case 'REALISASI_SUSTAIN':
                return 'REALISASI SUSTAIN YTD ' . getMonthName($month);
                break;

            case 'TOTAL_REALISASI_SCALING':
                return 'TOTAL REALISASI SCALING YTD ' . getMonthName($month);
                break;

            default:
                return 'UNKNOWN';
                break;
        }
    }

    private function generateData($month, $year): array
    {
        $realisasi = User::with(
            [
                'realisasiScalings' => function ($query) use ($month, $year) {
                    $query->select("user_id", "month", DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->groupBy('user_id', 'month');
                },
                'realisasiNgtmas' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiSustains' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                }
            ],
        )
            ->select("id", "name", "nik", "tr", "segmen", "level")
            ->groupBy("id")
            ->get()
            ->toArray();

        $dataRealisasi = $realisasi;
        $newDataRealisasi = [];

        foreach ($dataRealisasi as $key => $value) {
            $realisasi_scaling = $this->mappingMonthData($value['realisasi_scalings'], $month);
            $realisasi_sustains = $this->mappingMonthData($value['realisasi_sustains'], $month);
            $realisasi_ngtmas = $this->mappingMonthData($value['realisasi_ngtmas'], $month);

            array_push($newDataRealisasi, [
                "id" => $value['id'],
                "name" => $value['name'],
                "nik" => $value['nik'],
                "segmen" => $value["segmen"],
                "tr" => $value["tr"],
                "level" => $value["level"],
                "realisasi_scaling" => $realisasi_scaling,
                "realisasi_sustains" => $realisasi_sustains,
                "realisasi_ngtmas" => $realisasi_ngtmas,
            ]);
        }

        $target = User::with(
            [
                'targetScalings' => function ($query) use ($year) {
                    $query->select("user_id", DB::raw("sum(value_januari) as total_val_januari, sum(value_febuari) as total_val_febuari, sum(value_maret) as total_val_maret, sum(value_april) as total_val_april, sum(value_mei) as total_val_mei, sum(value_juni) as total_val_juni, sum(value_juli) as total_val_juli, sum(value_agustus) as total_val_agustus, sum(value_september) as total_val_september, sum(value_oktober) as total_val_oktober, sum(value_november) as total_val_november, sum(value_desember) as total_val_desember"));
                    $query->whereYear('created_at', '=', $year);
                    $query->groupBy('user_id');
                },
                'targetNgtmas' => function ($query) use ($year) {
                    $query->select('user_id', DB::raw("sum(value_januari) as total_val_januari, sum(value_febuari) as total_val_febuari, sum(value_maret) as total_val_maret, sum(value_april) as total_val_april, sum(value_mei) as total_val_mei, sum(value_juni) as total_val_juni, sum(value_juli) as total_val_juli, sum(value_agustus) as total_val_agustus, sum(value_september) as total_val_september, sum(value_oktober) as total_val_oktober, sum(value_november) as total_val_november, sum(value_desember) as total_val_desember"));
                    $query->whereYear('created_at', '=', $year);
                    $query->groupBy('user_id');
                },
                'targetSustains' => function ($query) use ($year) {
                    $query->select('user_id', DB::raw("sum(value_januari) as total_val_januari, sum(value_febuari) as total_val_febuari, sum(value_maret) as total_val_maret, sum(value_april) as total_val_april, sum(value_mei) as total_val_mei, sum(value_juni) as total_val_juni, sum(value_juli) as total_val_juli, sum(value_agustus) as total_val_agustus, sum(value_september) as total_val_september, sum(value_oktober) as total_val_oktober, sum(value_november) as total_val_november, sum(value_desember) as total_val_desember"));
                    $query->whereYear('created_at', '=', $year);
                    $query->groupBy('user_id');
                }
            ],
        )
            ->select("id", "name", "nik", "tr", "segmen", "level")
            ->groupBy("id")
            ->get()
            ->toArray();

        $newData = [];

        foreach ($target as $key => $value) {
            // dd($value);
            $target_scalings = $this->mappingMonthDataTarget($value['target_scalings'], $month);
            $target_ngtmas = $this->mappingMonthDataTarget($value['target_ngtmas'], $month);
            $target_sustains = $this->mappingMonthDataTarget($value['target_sustains'], $month);

            // dd($target_ngtmas);
            $realisasiIdx = array_search($value['id'], array_column($newDataRealisasi, 'id'));

            array_push($newData, [
                "id" => $value['id'],
                "name" => $value['name'],
                "nik" => $value['nik'],
                "segmen" => $value["segmen"],
                "tr" => $value["tr"],
                "level" => $value["level"],
                "target_scalings" => $target_scalings,
                "target_ngtmas" => $target_ngtmas,
                "target_sustains" => $target_sustains,
                "realisasi_scaling" => $newDataRealisasi[$realisasiIdx]['realisasi_scaling'],
                "realisasi_sustains" => $newDataRealisasi[$realisasiIdx]['realisasi_sustains'],
                "realisasi_ngtmas" => $newDataRealisasi[$realisasiIdx]['realisasi_ngtmas'],
            ]);
        }

        return $newData;
    }

    private function scoring($value): int
    {
        $scalingScore = 0;
        if (intval(number_format($value, 0)) > 0 || intval(number_format($value, 0)) <= 25) {
            $scalingScore = 1;
        } else if (intval(number_format($value, 0)) > 25 || intval(number_format($value, 0)) <= 50) {
            $scalingScore = 2;
        } else if (intval(number_format($value, 0)) > 50 || intval(number_format($value, 0)) <= 75) {
            $scalingScore = 3;
        } else if (intval(number_format($value, 0)) > 75 || intval(number_format($value, 0)) <= 99) {
            $scalingScore = 4;
        } else if (intval(number_format($value, 0)) >= 100) {
            $scalingScore = 5;
        }

        return $scalingScore;
    }

    public function exportReportExcel($month, $year)
    {
        $no = 1;
        $data = $this->generateData($month, $year);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // set header
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'NAMA AM');
        $sheet->setCellValue('D1', 'LEVEL AM');
        $sheet->setCellValue('E1', 'TR');
        $sheet->setCellValue('F1', 'WITEL/SEGMEN');

        $sheet->setCellValue('G1', 'TARGET SCALING YTD ' . getMonthName($month));
        $sheet->setCellValue('H1', 'REALISASI SCALING YTD ' . getMonthName($month));
        $sheet->setCellValue('I1', 'ACH SCALING (REALISASI DIBAGI TARGET)');
        $sheet->setCellValue('J1', 'TARGET NGTMA YTD ' . getMonthName($month));
        $sheet->setCellValue('K1', 'REALISASI NGTMA YTD' . getMonthName($month));
        $sheet->setCellValue('L1', 'ACH NGTMA (REALISASI DIBAGI TARGET)');
        $sheet->setCellValue('M1', 'TARGET SUSTAIN YTD ' . getMonthName($month));
        $sheet->setCellValue('N1', 'REALISASI SUSTAIN YTD ' . getMonthName($month));
        $sheet->setCellValue('O1', 'ACH SUSTAIN (REALISASI DIBAGI TARGET)');
        $sheet->setCellValue('P1', 'SKOR SCALING');
        $sheet->setCellValue('Q1', 'SKOR SUSTAIN');
        $sheet->setCellValue('R1', 'KUADRAN');

        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . ($no + 1), $no);

            // set body
            // profile
            $sheet->setCellValue('B' . ($no + 1), $value['nik']);
            $sheet->setCellValue('C' . ($no + 1), $value['name']);
            $sheet->setCellValue('D' . ($no + 1), $value['level']);
            $sheet->setCellValue('E' . ($no + 1), $value['tr']);
            $sheet->setCellValue('F' . ($no + 1), $value['segmen']);

            $achScaling = 0;
            $sheet->setCellValue('G' . ($no + 1), $value['target_scalings']['total']);
            $sheet->setCellValue('H' . ($no + 1), $value['realisasi_scaling']['total']);
            if ($value['target_scalings']['total'] > 0) {
                $achScaling = ($value['realisasi_scaling']['total'] / $value['target_scalings']['total']) * 100;
            }
            $sheet->setCellValue('I' . ($no + 1), number_format($achScaling, 2));
            $sheet->getStyle('I' . ($no + 1))
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

            $achNgtma = 0;
            $sheet->setCellValue('J' . ($no + 1), $value['target_ngtmas']['total']);
            $sheet->setCellValue('K' . ($no + 1), $value['realisasi_ngtmas']['total']);
            if ($value['realisasi_ngtmas']['total'] > 0) {
                $achNgtma = ($value['realisasi_ngtmas']['total'] / $value['target_ngtmas']['total']) * 100;
            }
            $sheet->setCellValue('L' . ($no + 1), number_format($achNgtma, 2));
            $sheet->getStyle('L' . ($no + 1))
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

            $achSustain = 0;
            $sheet->setCellValue('M' . ($no + 1), $value['target_sustains']['total']);
            $sheet->setCellValue('N' . ($no + 1), $value['realisasi_sustains']['total']);
            if ($value['realisasi_sustains']['total'] > 0) {
                $achNgtma = ($value['realisasi_sustains']['total'] / $value['target_sustains']['total']) * 100;
            }
            $sheet->setCellValue('O' . ($no + 1), number_format($achSustain, 2));
            $sheet->getStyle('O' . ($no + 1))
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

            // skoring
            $scalingScore = $this->scoring($achScaling);
            $sustainScore = $this->scoring($achSustain);

            $sheet->setCellValue('P' . ($no + 1), $scalingScore);
            $sheet->setCellValue('Q' . ($no + 1), $sustainScore);

            // kuadran
            $kuadran = "";
            if ($scalingScore == 5 && $sustainScore == 5) {
                $kuadran = "KUADRAN 1";
            } else if ($scalingScore == 5 && $sustainScore < 5) {
                $kuadran = "KUADRAN 2";
            } else if ($scalingScore < 5 && $sustainScore == 5) {
                $kuadran = "KUADRAN 3";
            } else if ($scalingScore < 5 && $sustainScore < 5) {
                $kuadran = "KUADRAN 4";
            }

            $sheet->setCellValue('R' . ($no + 1), $kuadran);

            $no++;
        }

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . time() . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);

        return  $writer->save('php://output');
    }

    public function exportRealisasi(string $type, string $tr)
    {
        $data = [];
        $no = 1;
        $row = 3;
        $value_year = 0;

        $contentDisposition = 'attachment; filename="realisasi_' . time() . '.xlsx"';
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:S1', ucwords($type));
        $sheet->setCellValue('A1', ucwords($type));
        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'NIK');
        $sheet->setCellValue('C2', 'NAMA');
        $sheet->setCellValue('D2', 'WITEL');
        $sheet->setCellValue('E2', 'REGION');
        $sheet->setCellValue('F2', 'LEVEL AM');
        $sheet->setCellValue('G2', 'Januari');
        $sheet->setCellValue('H2', 'Febuari');
        $sheet->setCellValue('I2', 'Maret');
        $sheet->setCellValue('J2', 'April');
        $sheet->setCellValue('K2', 'Mei');
        $sheet->setCellValue('L2', 'Juni');
        $sheet->setCellValue('M2', 'Juli');
        $sheet->setCellValue('N2', 'Agustus');
        $sheet->setCellValue('O2', 'September');
        $sheet->setCellValue('P2', 'Oktober');
        $sheet->setCellValue('Q2', 'November');
        $sheet->setCellValue('R2', 'Desember');
        $sheet->setCellValue('S2', 'Target Year');

        if (str_contains($tr, 'TR')) {
            if (str_contains($type, 'ngtma')) {
                $data = $this->realisasiNgtmaRepository->partTr();
            } else if (str_contains($type, 'sustain')) {
                $data = $this->realisasiSustainRepository->partTr();
            } else if (str_contains($type, 'scaling')) {
                $data = $this->realisasiScalingRepository->partTr();
            }
        } else {
            if (str_contains($type, 'ngtma')) {
                $data = $this->realisasiNgtmaRepository->partDbs();
            } else if (str_contains($type, 'sustain')) {
                $data = $this->realisasiSustainRepository->partDbs();
            } else if (str_contains($type, 'scaling')) {
                $data = $this->realisasiScalingRepository->partDbs();
            }
        }

        foreach ($data as $key => $value) {
            $value_year = $value['value_januari'] + $value['value_febuari'] + $value['value_maret'] + $value['value_april'] + $value['value_mei'] + $value['value_juni'] + $value['value_juli'] + $value['value_agustus'] + $value['value_september'] + $value['value_oktober'] + $value['value_november'] + $value['value_desember'];

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $value['nik']);
            $sheet->setCellValue('C' . $row, $value['name']);
            $sheet->setCellValue('D' . $row, $value['segmen']);
            $sheet->setCellValue('E' . $row, $value['tr']);
            $sheet->setCellValue('F' . $row, $value['segmen']);
            $sheet->setCellValue('G' . $row, $value['value_januari']);
            $sheet->setCellValue('H' . $row, $value['value_febuari']);
            $sheet->setCellValue('I' . $row, $value['value_maret']);
            $sheet->setCellValue('J' . $row, $value['value_april']);
            $sheet->setCellValue('K' . $row, $value['value_mei']);
            $sheet->setCellValue('L' . $row, $value['value_juni']);
            $sheet->setCellValue('M' . $row, $value['value_juli']);
            $sheet->setCellValue('N' . $row, $value['value_agustus']);
            $sheet->setCellValue('O' . $row, $value['value_september']);
            $sheet->setCellValue('P' . $row, $value['value_oktober']);
            $sheet->setCellValue('Q' . $row, $value['value_november']);
            $sheet->setCellValue('R' . $row, $value['value_desember']);
            $sheet->setCellValue('S' . $row, $value_year);

            $no++;
            $row++;
        }


        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', $contentDisposition);

        return $response;
    }

    public function exportTarget(string $type, string $tr)
    {
        $user_id = session()->get('user_id');
        $data = [];
        $no = 1;
        $row = 3;

        $contentDisposition = 'attachment; filename="target_' . time() . '.xlsx"';
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:S1', ucwords($type));
        $sheet->setCellValue('A1', ucwords($type));
        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'NIK');
        $sheet->setCellValue('C2', 'NAMA');
        $sheet->setCellValue('D2', 'WITEL');
        $sheet->setCellValue('E2', 'REGION');
        $sheet->setCellValue('F2', 'LEVEL AM');
        $sheet->setCellValue('G2', 'Januari');
        $sheet->setCellValue('H2', 'Febuari');
        $sheet->setCellValue('I2', 'Maret');
        $sheet->setCellValue('J2', 'April');
        $sheet->setCellValue('K2', 'Mei');
        $sheet->setCellValue('L2', 'Juni');
        $sheet->setCellValue('M2', 'Juli');
        $sheet->setCellValue('N2', 'Agustus');
        $sheet->setCellValue('O2', 'September');
        $sheet->setCellValue('P2', 'Oktober');
        $sheet->setCellValue('Q2', 'November');
        $sheet->setCellValue('R2', 'Desember');
        $sheet->setCellValue('S2', 'Target Year');

        if (str_contains($tr, 'TR')) {
            if (str_contains($type, 'ngtma')) {
                $data = TargetNgtma::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%TR%');
                    $query->where('id', '=', $user_id);
                })->get();
            } else if (str_contains($type, 'sustain')) {
                $data = TargetSustain::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%TR%');
                    $query->where('id', '=', $user_id);
                })->get();
            } else if (str_contains($type, 'scaling')) {
                $data = TargetScaling::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%TR%');
                    $query->where('id', '=', $user_id);
                })->get();
            }
        } else {
            if (str_contains($type, 'ngtma')) {
                $data = TargetNgtma::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%DBS%');
                    $query->where('id', '=', $user_id);
                })->get();
            } else if (str_contains($type, 'sustain')) {
                $data = TargetSustain::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%DBS%');
                    $query->where('id', '=', $user_id);
                })->get();
            } else if (str_contains($type, 'scaling')) {
                $data = TargetScaling::whereHas('users', function (Builder $query) use ($user_id) {
                    $query->where('tr', 'like', '%DBS%');
                    $query->where('id', '=', $user_id);
                })->get();
            }
        }

        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $value->users->nik);
            $sheet->setCellValue('C' . $row, $value->users->name);
            $sheet->setCellValue('D' . $row, $value->users->segmen);
            $sheet->setCellValue('E' . $row, $value->users->tr);
            $sheet->setCellValue('F' . $row, $value->users->segmen);
            $sheet->setCellValue('G' . $row, $value->value_januari);
            $sheet->setCellValue('H' . $row, $value->value_febuari);
            $sheet->setCellValue('I' . $row, $value->value_maret);
            $sheet->setCellValue('J' . $row, $value->value_april);
            $sheet->setCellValue('K' . $row, $value->value_mei);
            $sheet->setCellValue('L' . $row, $value->value_juni);
            $sheet->setCellValue('M' . $row, $value->value_juli);
            $sheet->setCellValue('N' . $row, $value->value_agustus);
            $sheet->setCellValue('O' . $row, $value->value_september);
            $sheet->setCellValue('P' . $row, $value->value_oktober);
            $sheet->setCellValue('Q' . $row, $value->value_november);
            $sheet->setCellValue('R' . $row, $value->value_desember);
            $sheet->setCellValue('S' . $row, $value->value_year);

            $no++;
            $row++;
        }


        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', $contentDisposition);

        return $response;
    }
}
