<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Setup\JenisPelanggaranController;
use App\Http\Controllers\Setup\PasalController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\WimController;
use App\Http\Controllers\DetailCaptureController;
use App\Http\Controllers\DetailPelanggaranController;
use App\Http\Controllers\DetailPasalController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReguController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DeviceController;

use App\Http\Controllers\Beranda\ResumeWidgetController;
use App\Http\Controllers\KendaraanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', RegisterController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);

    // guest verification (temporary auth)
    // Route::post('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verify');
    // Route::post('/verify-resend', [VerificationController::class, 'resend']);
});

Route::post('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verify');
Route::post('/verify-resend', [VerificationController::class, 'resend']);
//Route::get('/user', [AuthController::class, 'getUser']);

Route::get('verifikasi/pelanggaran/active/get', [PelanggaranController::class, 'active']);
Route::get('verifikasi/pelanggaran/publish/exportpdf', [PelanggaranController::class, 'exportpdf']);
Route::get('verifikasi/pelanggaran/publish/laporanpdf', [PelanggaranController::class, 'laporan']);
Route::get('verifikasi/pelanggaran/publish/laporanexcel', [PelanggaranController::class, 'exportexcel']);

Route::middleware('jwt.verify')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/profile', ProfileController::class);
    Route::patch('/password', PasswordController::class);
    // Route::get('/user', UserController::class);
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    // Route::get('/user', function (Request $request) {
    //     echo $request;
    //     return $request->user();
    // });
    // in app verification
    // Route::post('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verify');
    // Route::post('/verify-resend', [VerificationController::class, 'resend']);
    Route::get('beranda/resume', [ResumeWidgetController::class, 'resume']);
    Route::get('beranda/getperbulan', [ResumeWidgetController::class, 'getperbulan']);
    Route::get('beranda/getperjam', [ResumeWidgetController::class, 'getperjam']);
    Route::get('beranda/getperberat', [ResumeWidgetController::class, 'getperberat']);
    Route::get('beranda/getperjeniskendaraan', [ResumeWidgetController::class, 'getperjeniskendaraan']);
    Route::get('beranda/getperkategorikepemilikan', [ResumeWidgetController::class, 'getperkategorikepemilikan']);

    Route::resource('setup/jenispelanggaran', JenisPelanggaranController::class);
    Route::get('setup/jenispelanggaran/trush/publish', [JenisPelanggaranController::class, 'trush']);
    Route::get('setup/jenispelanggaran/active/publish', [JenisPelanggaranController::class, 'active']);
    Route::patch('setup/jenispelanggaran/active/update/{id}', [JenisPelanggaranController::class, 'updateActive']);
    Route::delete('setup/jenispelanggaran/delete/force/{id}', [JenisPelanggaranController::class, 'delete']);
    Route::delete('setup/jenispelanggaran/trush/arr/{id}', [JenisPelanggaranController::class, 'trushArr']);
    Route::put('setup/jenispelanggaran/restore/all', [JenisPelanggaranController::class, 'restoreAll']);
    Route::put('setup/jenispelanggaran/restore/one/{id}', [JenisPelanggaranController::class, 'restore']);

    Route::resource('setup/pasal', PasalController::class);
    Route::get('setup/pasal/trush/publish', [PasalController::class, 'trush']);
    Route::get('setup/pasal/active/publish', [PasalController::class, 'active']);
    Route::patch('setup/pasal/active/update/{id}', [PasalController::class, 'updateActive']);
    Route::delete('setup/pasal/delete/force/{id}', [PasalController::class, 'delete']);
    Route::delete('setup/pasal/trush/arr/{id}', [PasalController::class, 'trushArr']);
    Route::put('setup/pasal/restore/all', [PasalController::class, 'restoreAll']);
    Route::put('setup/pasal/restore/one/{id}', [PasalController::class, 'restore']);

    Route::resource('setup/device', DeviceController::class);

    Route::resource('verifikasi/capture', CaptureController::class);
    Route::post('verifikasi/capture/insert', [CaptureController::class, 'storeArr']);
    Route::get('verifikasi/capture/publish/sink', [CaptureController::class, 'sink_antrian']);
    Route::get('verifikasi/capture/trush/publish', [CaptureController::class, 'trush']);
    Route::get('verifikasi/capture/active/publish', [CaptureController::class, 'active']);
    Route::get('verifikasi/capture/active/getbydate', [CaptureController::class, 'getbydate']);
    Route::get('verifikasi/capture/active/getpage', [CaptureController::class, 'page']);
    Route::get('verifikasi/capture/active/getselected', [CaptureController::class, 'selected']);
    Route::patch('verifikasi/capture/active/update/{id}', [CaptureController::class, 'updateActive']);
    Route::patch('verifikasi/capture/active/edit/{id}', [CaptureController::class, 'updateVerif']);
    Route::delete('verifikasi/capture/delete/force/{id}', [CaptureController::class, 'delete']);
    Route::delete('verifikasi/capture/trush/arr/{id}', [CaptureController::class, 'trushArr']);
    Route::put('verifikasi/capture/restore/all', [CaptureController::class, 'restoreAll']);
    Route::put('verifikasi/capture/restore/one/{id}', [CaptureController::class, 'restore']);

    Route::resource('verifikasi/wim', WimController::class);
    Route::get('verifikasi/wim/active/getpage', [WimController::class, 'page']);
    Route::get('verifikasi/wim/active/getselected', [WimController::class, 'selected']);
    Route::delete('verifikasi/wim/delete/force/{id}', [WimController::class, 'delete']);

    Route::resource('verifikasi/pelanggaran', PelanggaranController::class);
    Route::post('verifikasi/pelanggaran/archive', [PelanggaranController::class, 'archive']);
    Route::post('verifikasi/pelanggaran/archivearr', [PelanggaranController::class, 'archiveArr']);
    Route::post('verifikasi/pelanggaran/createdetail', [PelanggaranController::class, 'createDetail']);
    Route::post('verifikasi/pelanggaran/insert', [PelanggaranController::class, 'storeArr']);
    Route::get('verifikasi/pelanggaran/trush/publish', [PelanggaranController::class, 'trush']);
    Route::get('verifikasi/pelanggaran/active/publish', [PelanggaranController::class, 'active']);
    Route::get('verifikasi/pelanggaran/active/getbydate', [PelanggaranController::class, 'getbydate']);
    Route::get('verifikasi/pelanggaran/active/exportpdf', [PelanggaranController::class, 'exportpdf']);
    Route::get('verifikasi/pelanggaran/active/laporanpdf', [PelanggaranController::class, 'laporan']);
    Route::get('verifikasi/pelanggaran/active/laporanexcel', [PelanggaranController::class, 'exportexcel']);
    Route::patch('verifikasi/pelanggaran/active/update/{id}', [PelanggaranController::class, 'updateActive']);
    Route::delete('verifikasi/pelanggaran/delete/force/{id}', [PelanggaranController::class, 'delete']);
    Route::delete('verifikasi/pelanggaran/trush/arr/{id}', [PelanggaranController::class, 'trushArr']);
    Route::put('verifikasi/pelanggaran/restore/all', [PelanggaranController::class, 'restoreAll']);
    Route::put('verifikasi/pelanggaran/restore/one/{id}', [PelanggaranController::class, 'restore']);

    Route::resource('verifikasi/kendaraan', KendaraanController::class);

    Route::resource('verifikasi/archive', ArchiveController::class);
    Route::get('verifikasi/archive/active/publish', [ArchiveController::class, 'arsipverifikasi']);
    Route::get('verifikasi/archive/active/selected', [ArchiveController::class, 'selected']);

    Route::get('laporan/perjeniskendaraan', [LaporanController::class, 'perjeniskendaraan']);
    Route::get('laporan/perjenispelanggaran', [LaporanController::class, 'perjenispelanggaran']);
    Route::get('laporan/perberat', [LaporanController::class, 'perberat']);
    Route::get('laporan/printpdf', [LaporanController::class, 'printpdf']);
    Route::get('laporan/exportexcel', [LaporanController::class, 'exportexcel']);

    Route::resource('pelanggaran/detailcapture', DetailCaptureController::class);
    Route::post('pelanggaran/detailcapture/insert', [DetailCaptureController::class, 'storeArr']);
    Route::get('pelanggaran/detailcapture/trush/publish', [DetailCaptureController::class, 'trush']);
    Route::get('pelanggaran/detailcapture/active/publish', [DetailCaptureController::class, 'active']);
    Route::patch('pelanggaran/detailcapture/active/update/{id}', [DetailCaptureController::class, 'updateActive']);
    Route::delete('pelanggaran/detailcapture/delete/force/{id}', [DetailCaptureController::class, 'delete']);
    Route::delete('pelanggaran/detailcapture/trush/arr/{id}', [DetailCaptureController::class, 'trushArr']);
    Route::put('pelanggaran/detailcapture/restore/all', [DetailCaptureController::class, 'restoreAll']);
    Route::put('pelanggaran/detailcapture/restore/one/{id}', [DetailCaptureController::class, 'restore']);

    Route::resource('pelanggaran/detailpelanggaran', DetailPelanggaranController::class);
    Route::post('pelanggaran/detailpelanggaran/insert', [DetailPelanggaranController::class, 'storeArr']);
    Route::get('pelanggaran/detailpelanggaran/trush/publish', [DetailPelanggaranController::class, 'trush']);
    Route::get('pelanggaran/detailpelanggaran/active/publish', [DetailPelanggaranController::class, 'active']);
    Route::patch('pelanggaran/detailpelanggaran/active/update/{id}', [DetailPelanggaranController::class, 'updateActive']);
    Route::delete('pelanggaran/detailpelanggaran/delete/force/{id}', [DetailPelanggaranController::class, 'delete']);
    Route::delete('pelanggaran/detailpelanggaran/trush/arr/{id}', [DetailPelanggaranController::class, 'trushArr']);
    Route::put('pelanggaran/detailpelanggaran/restore/all', [DetailPelanggaranController::class, 'restoreAll']);
    Route::put('pelanggaran/detailpelanggaran/restore/one/{id}', [DetailPelanggaranController::class, 'restore']);

    Route::resource('pelanggaran/detailpasal', DetailPasalController::class);
    Route::post('pelanggaran/detailpasal/insert', [DetailPasalController::class, 'storeArr']);
    Route::get('pelanggaran/detailpasal/trush/publish', [DetailPasalController::class, 'trush']);
    Route::get('pelanggaran/detailpasal/active/publish', [DetailPasalController::class, 'active']);
    Route::patch('pelanggaran/detailpasal/active/update/{id}', [DetailPasalController::class, 'updateActive']);
    Route::delete('pelanggaran/detailpasal/delete/force/{id}', [DetailPasalController::class, 'delete']);
    Route::delete('pelanggaran/detailpasal/trush/arr/{id}', [DetailPasalController::class, 'trushArr']);
    Route::put('pelanggaran/detailpasal/restore/all', [DetailPasalController::class, 'restoreAll']);
    Route::put('pelanggaran/detailpasal/restore/one/{id}', [DetailPasalController::class, 'restore']);

    Route::resource('shift', ShiftController::class);
    Route::get('shift/active/publish', [ShiftController::class, 'active']);

    Route::resource('regu', ReguController::class);
    Route::get('regu/active/publish', [ReguController::class, 'active']);
});
//Route::get('/user', UserController::class);
