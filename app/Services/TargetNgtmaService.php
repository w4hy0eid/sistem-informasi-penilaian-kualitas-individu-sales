<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Http\Requests\CreateTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\TargetNgtma;
use Illuminate\Database\Eloquent\Builder;

class TargetNgtmaService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function create(CreateTargetRequest $request)
    {
        $result = TargetNgtma::create([
            'value_januari' => $request->value_januari,
            'value_febuari' => $request->value_febuari,
            'value_maret' => $request->value_maret,
            'value_april' => $request->value_april,
            'value_mei' => $request->value_mei,
            'value_juni' => $request->value_juni,
            'value_juli' => $request->value_juli,
            'value_agustus' => $request->value_agustus,
            'value_september' => $request->value_september,
            'value_oktober' => $request->value_oktober,
            'value_november' => $request->value_november,
            'value_desember' => $request->value_desember,
            'value_year' => $request->value_year,
            'user_id' => $request->user_id
        ]);

        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        return $this->returnService->Return(NULL, true, "Successfully to create data");
    }

    public function update($id, UpdateTargetRequest $request)
    {
        $sustain = TargetNgtma::find($id);

        if (!$sustain) {
            return $this->returnService->Return(NULL, false, "Not Found");
        }

        $sustain->value_januari = $request->value_januari;
        $sustain->value_febuari = $request->value_febuari;
        $sustain->value_maret = $request->value_maret;
        $sustain->value_april = $request->value_april;
        $sustain->value_mei = $request->value_mei;
        $sustain->value_juni = $request->value_juni;
        $sustain->value_juli = $request->value_juli;
        $sustain->value_agustus = $request->value_agustus;
        $sustain->value_september = $request->value_september;
        $sustain->value_oktober = $request->value_oktober;
        $sustain->value_november = $request->value_november;
        $sustain->value_desember = $request->value_desember;
        $sustain->value_year = $request->value_year;
        $sustain->user_id = $request->user_id;
        $sustain->save();

        return $this->returnService->Return(NULL, true, "Successfully to update data");
    }

    public function list()
    {
        $role = session()->get('role');
        $user_id = session()->get('user_id');
        $tr = session()->get('tr');
        $resultDbs = [];
        $resultTreg = [];

        if (str_contains($tr, 'TR')) {
            $resultTreg = TargetNgtma::whereHas('users', function (Builder $query) use ($user_id, $role) {
                $query->where('tr', 'like', '%TR%');
                if($role != 'admin') {
                    $query->where('id', '=', $user_id);
                }
            })->get();

            $resultDbs = [];
        } else {
            $resultDbs = TargetNgtma::whereHas('users', function (Builder $query) use ($user_id, $role) {
                $query->where('tr', 'like', '%DBS%');
                if($role != 'admin') {
                    $query->where('id', '=', $user_id);
                }
            })->get();

            $resultTreg = [];
        }

        $map = [
            "dbs" => $resultDbs,
            "treg" => $resultTreg,
        ];

        return $this->returnService->Return($map, true, "Successfully");
    }

    public function singleShow($idUser)
    {
        $result = TargetNgtma::where('id', $idUser)->first();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function delete($id)
    {
        $user = TargetNgtma::find($id);
        $user->delete();

        return $this->returnService->Return(NULL, true, "Successfully");
    }
}
