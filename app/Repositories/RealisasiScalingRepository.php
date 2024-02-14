<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RealisasiScalingRepository
{
    public function partTr()
    {
        $role = session()->get('role');
        $newDataTreg = [];
        $resultTreg = DB::table("r_scaling")
            ->whereYear("r_scaling.created_at", "=", Date("Y"))
            ->leftJoin("users", "r_scaling.user_id", "=", "users.id")
            ->select("users.id as user_id", "users.name", "users.nik", "users.level", "users.tr", "users.segmen", "r_scaling.month", DB::raw("COALESCE(SUM(r_scaling.value), 0) as total_value"), "r_scaling.sales_id")
            ->where("users.tr", "like", "%TR%");

        if ($role != 'admin') {
            $resultTreg->where("users.id", session()->get('user_id'));
        }

        $get = $resultTreg->groupBy(["r_scaling.month", "users.id", "r_scaling.sales_id"])
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

    public function partDbs()
    {
        $role = session()->get('role');
        $newDataDbs = [];
        $resultDbs = DB::table("r_scaling")
            ->whereYear("r_scaling.created_at", "=", Date("Y"))
            ->leftJoin("users", "r_scaling.user_id", "=", "users.id")
            ->select("users.id as user_id", "users.name", "users.nik", "users.level", "users.tr", "users.segmen", "r_scaling.month", DB::raw("COALESCE(SUM(r_scaling.value), 0) as total_value"), "r_scaling.sales_id")
            ->where("users.tr", "like", "%DBS%");

        if ($role != 'admin') {
            $resultDbs->where("users.id", session()->get('user_id'));
        }

        $get = $resultDbs->groupBy(["r_scaling.month", "users.id", "r_scaling.sales_id"])
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
}
