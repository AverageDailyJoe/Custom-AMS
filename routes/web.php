<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HandoverFormController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/checkouts/{checkout}/pdf-handover', [HandoverFormController::class, 'downloadHandover'])->name('checkouts.pdf-handover');
    Route::get('/admin/checkouts/{checkout}/pdf-return', [HandoverFormController::class, 'downloadReturn'])->name('checkouts.pdf-return');
    Route::get('/admin/berita-acaras/{beritaAcara}/pdf', [HandoverFormController::class, 'downloadBeritaAcara'])->name('berita-acaras.pdf');
    Route::get('/admin/pengajuan-asets/{pengajuanAset}/pdf', [HandoverFormController::class, 'downloadPengajuanAset'])->name('pengajuan-asets.pdf');
});
