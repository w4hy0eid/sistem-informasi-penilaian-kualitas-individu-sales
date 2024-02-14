<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Http\Requests\CreateTargetRequest;
use App\Http\Requests\UpdateTargetRequest;
use App\Models\TargetScaling;
use Illuminate\Database\Eloquent\Builder;

class TargetScalingService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function create(CreateTargetRequest $request)
    {
        $result = TargetScaling::create([
            'value_month' => $request->value_month,
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
        $sustain = TargetScaling::find($id);

        if (!$sustain) {
            return $this->returnService->Return(NULL, false, "Not Found");
        }

        $sustain->value_month = $request->value_month;
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
            $resultTreg = TargetScaling::whereHas('users', function (Builder $query) use ($user_id, $role) {
                $query->where('tr', 'like', '%TR%');
                if ($role != 'admin') {
                    $query->where('id', '=', $user_id);
                }
            })->get();

            $resultDbs = [];
        } else {
            $resultDbs = TargetScaling::whereHas('users', function (Builder $query) use ($user_id, $role) {
                $query->where('tr', 'like', '%DBS%');
                if ($role != 'admin') {
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
        $result = TargetScaling::where('id', $idUser)->first();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function delete($id)
    {
        $user = TargetScaling::find($id);
        $user->delete();

        return $this->returnService->Return(NULL, true, "Successfully");
    }
}
