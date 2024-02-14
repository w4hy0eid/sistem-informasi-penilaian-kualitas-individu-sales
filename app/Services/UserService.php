<?php

namespace App\Services;

use App\Helpers\ReturnService;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(private ReturnService $returnService)
    {
    }

    public function singleShow($idUser)
    {
        $result = User::where('id', $idUser)->first();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function list()
    {
        $result = User::where('role', 'user')->orderByDesc('created_at')->get();

        return $this->returnService->Return($result, true, "Successfully");
    }

    public function create(CreateUserRequest $request)
    {
        $checkEmail = User::where('email', $request->email)->first();

        if ($checkEmail) {
            return $this->returnService->Return(NULL, false, "Email Duplicate");
        }

        $checkNik = User::where('nik', $request->nik)->first();

        if ($checkNik) {
            return $this->returnService->Return(NULL, false, "Nik Duplicate");
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'user',
            'tr' => $request->tr,
            'level' => $request->level,
            'nik' => $request->nik,
            'segmen' => $request->segmen,
            'password' => Hash::make($request->password),
        ]);

        if ($user == NULL) {
            return $this->returnService->Return(NULL, false, "Error");
        }

        return $this->returnService->Return(NULL, true, "Successfully");
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::find($id);

        if ($user == NULL) {
            return $this->returnService->Return(NULL, false, "Error");
        }

        $checkEmail = User::where('email', $request->email)->first();

        if ($checkEmail && $user->email != $checkEmail->email) {
            return $this->returnService->Return(NULL, false, "Email Duplicate");
        }

        $checkNik = User::where('nik', $request->nik)->first();

        if ($checkNik && $user->nik != $checkNik->nik) {
            return $this->returnService->Return(NULL, false, "Nik Duplicate");
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->tr = $request->tr;
        $user->level = $request->level;
        $user->segmen = $request->segmen;

        $user->save();

        return $this->returnService->Return(NULL, true, "Successfully");
    }

    public function delete($id)
    {
        $userAdmin = User::where('id', session()->get('user_id'))->first();

        if ($userAdmin->role !== 'admin') {
            return $this->returnService->Return(NULL, false, "Forbidden");
        }

        $user = User::find($id);
        $user->delete();

        return $this->returnService->Return(NULL, true, "Successfully");
    }

    public function importExcel()
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
            $email = $value['C'];

            // mapping data
            if ($numRow > 1) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    array_push($errorFound, "email already exist $email");
                    break;
                }

                if ($value['A'] == "") {
                    array_push($errorFound, "full name is required");
                    break;
                }

                if ($value['B'] == "") {
                    array_push($errorFound, "nik is required");
                    break;
                }

                if ($value['C'] == "") {
                    array_push($errorFound, "email is required");
                    break;
                }

                if ($value['D'] == "") {
                    array_push($errorFound, "segmen is required");
                    break;
                }

                if ($value['E'] == "") {
                    array_push($errorFound, "level is required");
                    break;
                }

                if ($value['F'] == "") {
                    array_push($errorFound, "tr is required");
                    break;
                }

                if ($value['G'] == "") {
                    array_push($errorFound, "password is required");
                    break;
                }

                if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) {
                    array_push($errorFound, "Invalid email format " . $email);
                    break;
                }

                array_push($saveData, [
                    'name' => $value['A'],
                    'email' => trim($email),
                    'role' => 'user',
                    'tr' => $value['F'],
                    'level' => $value['E'],
                    'nik' => $value['B'],
                    'segmen' => $value['D'],
                    'password' => Hash::make(trim($value['G'])),
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

        $result = User::insert($saveData);

        if ($result == NULL) {
            return $this->returnService->Return(NULL, false, "Error to create data");
        }

        return $this->returnService->Return(NULL, true, "Successfully to import");
    }

    public function totalPembayaran()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        $total = 0;

        if ($role === 'admin') {
            $total = DB::select(DB::raw("SELECT SUM(total_r.total_r_ngtma + total_r.total_r_scaling + total_r.total_r_sustain) as total_all_pembayaran FROM (SELECT SUM(r_ngtma.value) as total_r_ngtma, SUM(r_scaling.value) as total_r_scaling, SUM(r_sustain.value) as total_r_sustain FROM users LEFT JOIN r_ngtma ON users.id=r_ngtma.user_id LEFT JOIN r_scaling ON users.id=r_scaling.user_id LEFT JOIN r_sustain ON users.id=r_sustain.user_id) as total_r"));
        } else {
            $total = DB::select(DB::raw("SELECT SUM(total_r.total_r_ngtma + total_r.total_r_scaling + total_r.total_r_sustain) as total_all_pembayaran
            FROM
            (SELECT SUM(r_ngtma.value) as total_r_ngtma,
            SUM(r_scaling.value) as total_r_scaling,
            SUM(r_sustain.value) as total_r_sustain
            FROM users
            LEFT JOIN r_ngtma ON users.id=r_ngtma.user_id
            LEFT JOIN r_scaling ON users.id=r_scaling.user_id
            LEFT JOIN r_sustain ON users.id=r_sustain.user_id
            WHERE users.id=$userId) as total_r"));
        }

        // dd($total);

        return $total[0]->total_all_pembayaran;
    }

    public function totalUser(): int
    {
        $total = User::where('role', 'user')->count();

        return $total;
    }
}
