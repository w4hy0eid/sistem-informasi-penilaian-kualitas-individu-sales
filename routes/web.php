<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\RealisasiNgtmaController;
use App\Http\Controllers\RealisasiScalingController;
use App\Http\Controllers\RealisasiSustainController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TargetNgtmaController;
use App\Http\Controllers\TargetScalingController;
use App\Http\Controllers\TargetSustainController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/view/realisasi-ngtma-detail/{id}', [RealisasiNgtmaController::class, 'detail'])->middleware('is.login');
Route::get('/view/realisasi-sustain-detail/{id}', [RealisasiSustainController::class, 'detail'])->middleware('is.login');
Route::get('/view/realisasi-scaling-detail/{id}', [RealisasiScalingController::class, 'detail'])->middleware('is.login');

Route::get('/view/realisasi-scaling', [RealisasiScalingController::class, 'index'])->middleware('is.login');
Route::get('/view/realisasi-ngtma', [RealisasiNgtmaController::class, 'index'])->middleware('is.login');
Route::get('/view/realisasi-sustain', [RealisasiSustainController::class, 'index'])->middleware('is.login');
Route::get('/view/target-scaling', [TargetScalingController::class, 'index'])->middleware('is.login');
Route::get('/view/target-ngtma', [TargetNgtmaController::class, 'index'])->middleware('is.login');
Route::get('/view/target-sustain', [TargetSustainController::class, 'index'])->middleware('is.login');
Route::get('/view/user', [UserController::class, 'index'])->middleware('is.login');
Route::get('/view/sales', [SalesController::class, 'index'])->middleware('is.login');
Route::get('/view/dashboard', [AuthController::class, 'ViewDashboard'])->middleware('is.login');
Route::get('/view/change-password', [ChangePasswordController::class, 'index'])->middleware('is.login');
Route::get('/view/generate-report', [ExportController::class, 'index'])->middleware('is.login');

Route::get('/view/login', [AuthController::class, 'ViewLogin']);
Route::get('/', function() {
    if (session()->get('is_login') === TRUE) {
        return redirect('/view/dashboard');
    }
    return redirect('/view/login');
});

Route::get('/users', [UserController::class, 'list']);

Route::get('/user/{id}', [UserController::class, 'singleData']);
Route::get('/realisasi/sustain/{id}', [RealisasiSustainController::class, 'singleData']);
Route::get('/realisasi/ngtma/{id}', [RealisasiNgtmaController::class, 'singleData']);
Route::get('/target/sustain/{id}', [TargetSustainController::class, 'singleData']);
Route::get('/target/scaling/{id}', [TargetScalingController::class, 'singleData']);
Route::get('/target/ngtma/{id}', [TargetNgtmaController::class, 'singleData']);
Route::get('/sales/{id}', [SalesController::class, 'singleData']);

Route::get('/r-ngtma/user/{userId}/sales/{salesId}', [RealisasiNgtmaController::class, 'listDetail']);
Route::get('/r-sustain/user/{userId}/sales/{salesId}', [RealisasiSustainController::class, 'listDetail']);
Route::get('/r-scaling/user/{userId}/sales/{salesId}', [RealisasiScalingController::class, 'listDetail']);

// import
Route::post('/action/import-sales', [ImportController::class, 'importExcel'])->name('/action/import-sales');
Route::post('/action/import-target/{type}', [ImportController::class, 'importTarget'])->name('/action/import-target');

// generate
Route::post('/action/generate-report', [ExportController::class, 'ExportAll'])->name('/action/generate-report');

// export
Route::post('/action/export-target', [ExportController::class, 'exportTarget'])->name('/action/export-target');
Route::post('/action/export-realisasi', [ExportController::class, 'exportRealisasi'])->name('/action/export-realisasi');

// sales
Route::delete('/action/sales/delete/{id}', [SalesController::class, 'delete'])->name('/action/sales/delete');
Route::put('/action/sales/update/{id}', [SalesController::class, 'update'])->name('/action/sales/update');
Route::post('/action/sales/create', [SalesController::class, 'create'])->name('/action/sales/create');

// target
// --> sustain
Route::delete('/action/target/sustain/delete/{id}', [TargetSustainController::class, 'delete'])->name('/action/target/sustain/delete');
Route::put('/action/target/sustain/update/{id}', [TargetSustainController::class, 'update'])->name('/action/target/sustain/update');
Route::post('/action/target/sustain/create', [TargetSustainController::class, 'create'])->name('/action/target/sustain/create');
// --> scaling
Route::delete('/action/target/scaling/delete/{id}', [TargetScalingController::class, 'delete'])->name('/action/target/scaling/delete');
Route::put('/action/target/scaling/update/{id}', [TargetScalingController::class, 'update'])->name('/action/target/scaling/update');
Route::post('/action/target/scaling/create', [TargetScalingController::class, 'create'])->name('/action/target/scaling/create');
// --> ngtma
Route::delete('/action/target/ngtma/delete/{id}', [TargetNgtmaController::class, 'delete'])->name('/action/target/ngtma/delete');
Route::put('/action/target/ngtma/update/{id}', [TargetNgtmaController::class, 'update'])->name('/action/target/ngtma/update');
Route::post('/action/target/ngtma/create', [TargetNgtmaController::class, 'create'])->name('/action/target/ngtma/create');

// realisasi
// --> sustain
Route::delete('/action/realisasi/sustain/delete/{id}', [RealisasiSustainController::class, 'delete'])->name('/action/realisasi/sustain/delete');
Route::put('/action/realisasi/sustain/update/{id}', [RealisasiSustainController::class, 'update'])->name('/action/realisasi/sustain/update');
Route::post('/action/realisasi/sustain/create', [RealisasiSustainController::class, 'create'])->name('/action/realisasi/sustain/create');
// --> ngtma
Route::delete('/action/realisasi/ngtma/delete/{id}', [RealisasiNgtmaController::class, 'delete'])->name('/action/realisasi/ngtma/delete');
Route::put('/action/realisasi/ngtma/update/{id}', [RealisasiNgtmaController::class, 'update'])->name('/action/realisasi/ngtma/update');
Route::post('/action/realisasi/ngtma/create', [RealisasiNgtmaController::class, 'create'])->name('/action/realisasi/ngtma/create');
// --> scaling
Route::delete('/action/realisasi/scaling/delete/{id}', [RealisasiScalingController::class, 'delete'])->name('/action/realisasi/scaling/delete');
Route::put('/action/realisasi/scaling/update/{id}', [RealisasiScalingController::class, 'update'])->name('/action/realisasi/scaling/update');
Route::post('/action/realisasi/scaling/create', [RealisasiScalingController::class, 'create'])->name('/action/realisasi/scaling/create');

// user
Route::post('/action/user/import', [UserController::class, 'import'])->name('/action/user/import');
Route::delete('/action/user/delete/{id}', [UserController::class, 'delete'])->name('/action/user/delete');
Route::put('/action/user/update/{id}', [UserController::class, 'update'])->name('/action/user/update');
Route::post('/action/user/create', [UserController::class, 'create'])->name('/action/user/create');
Route::post('/auth/login', [AuthController::class, 'Login'])->name('/auth/login');
Route::any('/auth/logout', [AuthController::class, 'Logout'])->name('/auth/logout');
Route::post('/action/change-password', [ChangePasswordController::class, 'changePassword'])->name('/action/change-password');
