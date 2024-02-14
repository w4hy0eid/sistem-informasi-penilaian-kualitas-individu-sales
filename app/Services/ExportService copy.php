<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExportService
{
    private function mappingMonthData($data, $setMonth)
    {

        $month = [];
        $save = [];

        for ($i=1; $i <= $setMonth; $i++) {
            array_push($month, $i);
        }

        if (count($data) >= 0) {
            $data_target_ngtmas = $data;
            $remap_target_ngtma = [];
            $total_target_ngtma = 0;

            for ($i = 0; $i < count($month); $i++) {
                if (array_search($month[$i], array_column($data, 'month')) !== FALSE) {
                    $key = array_search($month[$i], array_column($data, 'month'));
                    $total_target_ngtma += $data_target_ngtmas[$key]['total_val'];
                    array_push($remap_target_ngtma, [
                        'month' => $data_target_ngtmas[$key]['month'],
                        'value' => $data_target_ngtmas[$key]['total_val'],
                    ]);
                } else {
                    array_push($remap_target_ngtma, [
                        'month' => $month[$i],
                        'value' => 0,
                    ]);
                }
            }

            array_push($save, [
                'data' => $remap_target_ngtma,
                'total' => $total_target_ngtma,
            ]);
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

    private function setHeaderScalingAndSustain($sheet, $data, $collectionCell, $index, $title, $moveIdxCell)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $achTitleCm = "ACH. " . $title . " CM " . getMonthName(count($data));
        $ketAchTitleCm = "KET ACH. " . $title . " CM " . getMonthName(count($data));
        $achTitleYtd = "ACH. " . $title . " CM " . getMonthName(count($data));
        $ketAchTitleYtd = "KET ACH. " . $title . " CM " . getMonthName(count($data));

        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($data)) - count($data) + $increaseCell;

        $output->writeln("startIdx [" . $startIdx . "]" . "endIdx [" . $startIdx + 3 . "]" . " increaseCell [" . $increaseCell . "]");

        $sheet->setCellValue($collectionCell[$startIdx] . '1', $achTitleCm);
        $sheet->setCellValue($collectionCell[$startIdx + 1] . '1', $ketAchTitleCm);
        $sheet->setCellValue($collectionCell[$startIdx + 2] . '1', $achTitleYtd);
        $sheet->setCellValue($collectionCell[$startIdx + 3] . '1', $ketAchTitleYtd);
    }

    private function setHeaderDynamic($sheet, $data, $collectionCell, $index, $title, $moveIdxCell)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($data)) - count($data) + $increaseCell;
        $endIdx = $startIdx + count($data);
        $no = 1;

        $output->writeln("index " . $index . " startIdx [" . $startIdx . "]" . "endIdx [" . $endIdx . "]" . " increaseCell [" . $increaseCell . "]");

        for ($i = $startIdx; $i < $endIdx; $i++) {
            $sheet->setCellValue($collectionCell[$i] . '1', $this->setNameHeader($title) . ' ' . ($no));

            $no++;
        }

        // $endIdx += 1;
        $sheet->setCellValue($collectionCell[$endIdx] . '1', $this->setNameHeaderTotal($title, count($data)));
    }

    private function setTotalRealisasiScaling($sheet, $data, $collectionCell, $key, $index, $moveIdxCell)
    {
        $sampleData = $data['realisasi_pots'];
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($sampleData)) - count($sampleData) + $increaseCell;
        $endIdx = $startIdx + count($sampleData);
        $no = 1;
        $idxData = 0;
        $totalSum = 0;
        $key += 1;
        $lastValue = 0;

        // $output->writeln("startIdx [" . $startIdx . "]" . "endIdx [" . $endIdx . "]" . " increaseCell [" . $increaseCell . "]");

        for ($i = $startIdx; $i < $endIdx; $i++) {
            $sum = $data['realisasi_pots'][$idxData]['value'] + $data['realisasi_connectivities'][$idxData]['value'] + $data['realisasi_digitals'][$idxData]['value'] + $data['realisasi_eksis'][$idxData]['value'] + $data['realisasi_wifis'][$idxData]['value'];

            $sheet->setCellValue($collectionCell[$i] . $key, $sum);

            if (($endIdx - 1) === $i) {
                $output->writeln("sum --->" . $sum);
                $lastValue = $sum;
            }

            $totalSum += $sum;
            $no++;
            $idxData++;
        }

        // $endIdx += 1;
        $sheet->setCellValue($collectionCell[$endIdx] . $key, $totalSum);

        return $lastValue;
    }

    private function setValueDynamic($sheet, $data, $collectionCell, $key, $index, $moveIdxCell)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $startData = $data[0]['data'];
        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($startData)) - count($startData) + $increaseCell;
        $endIdx = $startIdx + count($startData);
        $key += 1;
        $indx = 0;

        for ($i = $startIdx; $i < $endIdx; $i++) {
            $sheet->setCellValue($collectionCell[$i] . $key, $startData[$indx]['value']);
            // $output->writeln($collectionCell[$i] . $key);
            $indx++;
        }

        // $endIdx += 1;
        $sheet->setCellValue($collectionCell[$endIdx] . $key, $data[0]['total']);
    }

    private function setBodyScaling($sheet, $data, $collectionCell, $key, $index, $moveIdxCell, $sumRealisasiScaling)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($data['target_scalings']['data'])) - count($data['target_scalings']['data']) + $increaseCell;
        $key += 1;
        $lastIdx = count($data['target_scalings']['data']) - 1;

        $caclAchCm = $sumRealisasiScaling / ($data['target_scalings']['data'][$lastIdx]['value'] === 0 ? 1 : $data['target_scalings']['data'][$lastIdx]['value']);
        $percentageCaclAchCm = number_format($caclAchCm * 100, 1);
        $ketAchCm = $caclAchCm * 100 < 100 ? "<100%" : ">100%";

        $achYtd = $sumRealisasiScaling / $data['target_scalings']['total'] === 0 ? 1 : $data['target_scalings']['total'];
        $percentageAchYtd = number_format($achYtd * 100, 1);
        $ketAchYtd = $achYtd * 100 < 100 ? "<100%" : ">100%";

        // $output->writeln("startIdx [" . $startIdx . "]" . "endIdx [" . $startIdx + 3 . "]" . " increaseCell [" . $increaseCell . "]");

        $sheet->setCellValue($collectionCell[$startIdx] . $key, $percentageCaclAchCm);
        $sheet->setCellValue($collectionCell[$startIdx + 1] . $key, $ketAchCm);
        $sheet->setCellValue($collectionCell[$startIdx + 2] . $key, $percentageAchYtd);
        $sheet->setCellValue($collectionCell[$startIdx + 3] . $key, $ketAchYtd);
    }

    private function setBodySustain($sheet, $data, $collectionCell, $key, $index, $moveIdxCell, $sumRealisasiScaling)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $increaseCell = (($index - 1) + $moveIdxCell);
        $startIdx = ($index * count($data['realisasi_sustains']['data'])) - count($data['realisasi_sustains']['data']) + $increaseCell;
        $key += 1;
        $lastIdx = count($data['realisasi_sustains']['data']) - 1;

        $caclAchCm = $data['realisasi_sustains']['data'][$lastIdx]['value'] / ($data['target_sustains']['data'][$lastIdx]['value'] === 0 ? 1 : $data['target_sustains']['data'][$lastIdx]['value']);
        $percentageCaclAchCm = number_format($caclAchCm * 100, 1);
        $ketAchCm = $caclAchCm * 100 < 100 ? "<100%" : ">100%";

        $achYtd = $data['realisasi_sustains']['total'] / $data['target_sustains']['total'] === 0 ? 1 : $data['target_sustains']['total'];
        $percentageAchYtd = number_format($achYtd * 100, 1);
        $ketAchYtd = $achYtd * 100 < 100 ? "<100%" : ">100%";

        // $output->writeln("startIdx [" . $startIdx . "]" . "endIdx [" . $startIdx + 3 . "]" . " increaseCell [" . $increaseCell . "]");

        $sheet->setCellValue($collectionCell[$startIdx] . $key, $percentageCaclAchCm);
        $sheet->setCellValue($collectionCell[$startIdx + 1] . $key, $ketAchCm);
        $sheet->setCellValue($collectionCell[$startIdx + 2] . $key, $percentageAchYtd);
        $sheet->setCellValue($collectionCell[$startIdx + 3] . $key, $ketAchYtd);
    }

    public function exportReportExcel($month, $year)
    {
        $contentDisposition = 'attachment; filename="' . time() . '.xlsx"';
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $d = User::with(
            [
                'targetNgtmas' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'targetSustains' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'targetScalings' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiWifis' => function ($query) use ($month, $year) {
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
                },
                'realisasiPots' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiNgtmas' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiEksis' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiDigitals' => function ($query) use ($month, $year) {
                    $query->select('month', 'user_id', DB::raw("sum(value) as total_val"));
                    $query->whereBetween('month', [1, $month]);
                    $query->whereYear('created_at', '=', $year);
                    $query->orderBy('month', 'asc');
                    $query->groupBy('user_id', 'month');
                },
                'realisasiConnectivities' => function ($query) use ($month, $year) {
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

        $data = $d;
        $newData = [];
        $no = 1;

        foreach ($data as $key => $value) {
            $target_ngtmas = $this->mappingMonthData($value['target_ngtmas'], $month);
            $target_sustains = $this->mappingMonthData($value['target_sustains'], $month);
            $target_scalings = $this->mappingMonthData($value['target_scalings'], $month);
            $realisasi_wifis = $this->mappingMonthData($value['realisasi_wifis'], $month);
            $realisasi_sustains = $this->mappingMonthData($value['realisasi_sustains'], $month);
            $realisasi_pots = $this->mappingMonthData($value['realisasi_pots'], $month);
            $realisasi_ngtmas = $this->mappingMonthData($value['realisasi_ngtmas'], $month);
            $realisasi_eksis = $this->mappingMonthData($value['realisasi_eksis'], $month);
            $realisasi_digitals = $this->mappingMonthData($value['realisasi_digitals'], $month);
            $realisasi_connectivities = $this->mappingMonthData($value['realisasi_connectivities'], $month);

            array_push($newData, [
                "name" => $value['name'],
                "nik" => $value['nik'],
                "segmen" => $value["segmen"],
                "tr" => $value["tr"],
                "level" => $value["level"],
                "target_ngtmas" => $target_ngtmas,
                "target_sustains" => $target_sustains,
                "target_scalings" => $target_scalings,
                "realisasi_wifis" => $realisasi_wifis,
                "realisasi_sustains" => $realisasi_sustains,
                "realisasi_pots" => $realisasi_pots,
                "realisasi_ngtmas" => $realisasi_ngtmas,
                "realisasi_eksis" => $realisasi_eksis,
                "realisasi_digitals" => $realisasi_digitals,
                "realisasi_connectivities" => $realisasi_connectivities,
            ]);
        }

        // dd($newData);

        // set header
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'NAMA AM');
        $sheet->setCellValue('D1', 'LEVEL AM');
        $sheet->setCellValue('E1', 'TR');
        $sheet->setCellValue('F1', 'WITEL/SEGMEN');

        $cell = ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ', 'GA', 'GB', 'GC', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GJ', 'GK', 'GL', 'GM', 'GN', 'GO', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GV', 'GW', 'GX', 'GY', 'GZ', 'HA', 'HB', 'HC', 'HD', 'HE', 'HF', 'HG', 'HH', 'HI', 'HJ', 'HK', 'HL', 'HM', 'HN', 'HO', 'HP', 'HQ', 'HR', 'HS', 'HT', 'HU', 'HV', 'HW', 'HX', 'HY', 'HZ', 'IA', 'IB', 'IC', 'ID', 'IE', 'IF', 'IG', 'IH', 'II', 'IJ', 'IK', 'IL', 'IM', 'IN', 'IO', 'IP', 'IQ', 'IR', 'IS', 'IT', 'IU', 'IV', 'IW', 'IX', 'IY', 'IZ', 'JA', 'JB', 'JC', 'JD', 'JE', 'JF', 'JG', 'JH', 'JI', 'JJ', 'JK', 'JL', 'JM', 'JN', 'JO', 'JP', 'JQ', 'JR', 'JS', 'JT', 'JU', 'JV', 'JW', 'JX', 'JY', 'JZ', 'KA', 'KB', 'KC', 'KD', 'KE', 'KF', 'KG', 'KH', 'KI', 'KJ', 'KK', 'KL', 'KM', 'KN', 'KO', 'KP', 'KQ', 'KR', 'KS', 'KT', 'KU', 'KV', 'KW', 'KX', 'KY', 'KZ'];

        foreach ($newData as $key => $value) {
            // set nomor
            $sheet->setCellValue('A' . ($no + 1), $no);
            // set header
            $this->setHeaderDynamic($sheet, $value['target_scalings'][0]['data'], $cell, 1, 'TARGET_SCALING', 0);
            $this->setHeaderDynamic($sheet, $value['target_ngtmas'][0]['data'], $cell, 2, 'TARGET_NGTMAS', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_pots'][0]['data'], $cell, 3, 'REALISASI_POTS', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_connectivities'][0]['data'], $cell, 4, 'REALISASI_CONNECTIVITY', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_digitals'][0]['data'], $cell, 5, 'REALISASI_DIGITAL', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_eksis'][0]['data'], $cell, 6, 'REALISASI_EKSIS', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_wifis'][0]['data'], $cell, 7, 'REALISASI_WIFI', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_ngtmas'][0]['data'], $cell, 8, 'REALISASI_NGTMA', 0);
            $this->setHeaderDynamic($sheet, $value['realisasi_ngtmas'][0]['data'], $cell, 9, 'TOTAL_REALISASI_SCALING', 0);
            $this->setHeaderScalingAndSustain($sheet, $value['realisasi_ngtmas'][0]['data'], $cell, 10, 'SCALING', 0);
            $this->setHeaderDynamic($sheet, $value['target_sustains'][0]['data'], $cell, 10, 'TARGET_SUSTAIN', 4);
            $this->setHeaderDynamic($sheet, $value['realisasi_sustains'][0]['data'], $cell, 11, 'REALISASI_SUSTAIN', 4);
            $this->setHeaderScalingAndSustain($sheet, $value['realisasi_ngtmas'][0]['data'], $cell, 12, 'SUSTAIN', 4);

            // set body
            // profile
            $sheet->setCellValue('B' . ($no + 1), $value['nik']);
            $sheet->setCellValue('C' . ($no + 1), $value['name']);
            $sheet->setCellValue('D' . ($no + 1), $value['level']);
            $sheet->setCellValue('E' . ($no + 1), $value['tr']);
            $sheet->setCellValue('F' . ($no + 1), $value['segmen']);

            // realisasi & target
            $this->setValueDynamic($sheet, $value['target_scalings'], $cell, ($key + 1), 1, 0);
            $this->setValueDynamic($sheet, $value['target_ngtmas'], $cell, ($key + 1), 2, 0);
            $this->setValueDynamic($sheet, $value['realisasi_pots'], $cell, ($key + 1), 3, 0);
            $this->setValueDynamic($sheet, $value['realisasi_connectivities'], $cell, ($key + 1), 4, 0);
            $this->setValueDynamic($sheet, $value['realisasi_digitals'], $cell, ($key + 1), 5, 0);
            $this->setValueDynamic($sheet, $value['realisasi_eksis'], $cell, ($key + 1), 6, 0);
            $this->setValueDynamic($sheet, $value['realisasi_wifis'], $cell, ($key + 1), 7, 0);
            $this->setValueDynamic($sheet, $value['realisasi_ngtmas'], $cell, ($key + 1), 8, 0);

            $x = $this->setTotalRealisasiScaling(
                $sheet,
                [
                    'realisasi_pots' => $value['realisasi_pots'][0]['data'],
                    'realisasi_connectivities' => $value['realisasi_connectivities'][0]['data'],
                    'realisasi_digitals' => $value['realisasi_digitals'][0]['data'],
                    'realisasi_eksis' => $value['realisasi_eksis'][0]['data'],
                    'realisasi_wifis' => $value['realisasi_wifis'][0]['data'],
                ],
                $cell,
                ($key + 1),
                9,
                0
            );

            $this->setBodyScaling(
                $sheet,
                [
                    'target_scalings' => [
                        'data' => $value['target_scalings'][0]['data'],
                        'total' => $value['target_scalings'][0]['total']
                    ],
                ],
                $cell,
                ($key + 1),
                10,
                0,
                $x
            );

            $this->setValueDynamic($sheet, $value['target_sustains'], $cell, ($key + 1), 10, 4);
            $this->setValueDynamic($sheet, $value['realisasi_sustains'], $cell, ($key + 1), 11, 4);

            $this->setBodySustain(
                $sheet,
                [
                    'target_sustains' => [
                        'data' => $value['target_sustains'][0]['data'],
                        'total' => $value['target_sustains'][0]['total']
                    ],
                    'realisasi_sustains' => [
                        'data' => $value['realisasi_sustains'][0]['data'],
                        'total' => $value['realisasi_sustains'][0]['total']
                    ],
                ],
                $cell,
                ($key + 1),
                12,
                4,
                $x
            );


            $no++;
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
