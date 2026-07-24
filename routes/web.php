<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HandoverFormController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/checkouts/{checkout}/pdf-handover', [HandoverFormController::class, 'downloadHandover'])->name('checkouts.pdf-handover');
    Route::get('/admin/checkouts/{checkout}/pdf-return', [HandoverFormController::class, 'downloadReturn'])->name('checkouts.pdf-return');
});
