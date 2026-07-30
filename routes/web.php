<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HandoverFormController;
use App\Http\Controllers\Auth\OtpRegisterController;
use App\Http\Controllers\Auth\OtpResetPasswordController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/checkouts/{checkout}/pdf-handover', [HandoverFormController::class, 'downloadHandover'])->name('checkouts.pdf-handover');
    Route::get('/admin/checkouts/{checkout}/pdf-return', [HandoverFormController::class, 'downloadReturn'])->name('checkouts.pdf-return');
    Route::get('/admin/berita-acaras/{beritaAcara}/pdf', [HandoverFormController::class, 'downloadBeritaAcara'])->name('berita-acaras.pdf');
    Route::get('/admin/pengajuan-asets/{pengajuanAset}/pdf-ppb', [HandoverFormController::class, 'downloadPengajuanAset'])->name('pengajuan-asets.pdf-ppb');
    Route::get('/admin/pengajuan-asets/{pengajuanAset}/pdf-lbs', [HandoverFormController::class, 'downloadLBS'])->name('pengajuan-asets.pdf-lbs');
    Route::get('/admin/pengajuan-asets/{pengajuanAset}/pdf', [HandoverFormController::class, 'downloadPengajuanAset'])->name('pengajuan-asets.pdf');
    Route::get('/admin/dispose-asets/{disposeAset}/pdf', [HandoverFormController::class, 'downloadDisposal'])->name('dispose-asets.pdf');
    Route::get('/admin/tickets/{ticket}/pdf', [HandoverFormController::class, 'downloadTicket'])->name('tickets.pdf');
    Route::get('/admin/rekap-aset/pdf', [HandoverFormController::class, 'downloadRekapAset'])->name('rekap-aset.pdf');
    Route::get('/admin/rekap-aset/excel', [HandoverFormController::class, 'exportAsetExcel'])->name('rekap-aset.excel');
    Route::get('/admin/rekap-tiket/pdf', [HandoverFormController::class, 'downloadRekapTiket'])->name('rekap-tiket.pdf');

});

// Storage file serving route to guarantee 200 OK preview and download
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        $filePath = storage_path('app/' . $path);
    }
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.file');

// Routes Registrasi & OTP
Route::get('/register', [OtpRegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [OtpRegisterController::class, 'register']);
Route::get('/register/verify-otp', [OtpRegisterController::class, 'showVerifyForm'])->name('otp.verify.show');
Route::post('/register/verify-otp', [OtpRegisterController::class, 'verify'])->name('otp.verify');
// Routes Reset Password & OTP
Route::get('/forgot-password', [OtpResetPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [OtpResetPasswordController::class, 'sendResetOtp'])->name('password.email');
Route::get('/reset-password-otp', [OtpResetPasswordController::class, 'showResetForm'])->name('password.reset.verify.show');
Route::post('/reset-password-otp', [OtpResetPasswordController::class, 'resetPassword'])->name('password.update');
