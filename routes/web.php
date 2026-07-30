<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HandoverFormController;

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
