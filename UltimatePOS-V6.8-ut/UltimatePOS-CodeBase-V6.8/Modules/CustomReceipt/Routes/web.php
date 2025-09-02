<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomReceipt\Http\Controllers\CustomReceiptController;

Route::middleware(['auth', 'setData'])->group(function () {
    Route::prefix('custom-receipt')->group(function () {
        Route::get('/', [CustomReceiptController::class, 'index'])->name('custom-receipt.index');
        Route::get('/generate/{id}', [CustomReceiptController::class, 'generateReceipt'])->name('custom-receipt.generate');
        Route::get('/print/{id}', [CustomReceiptController::class, 'printReceipt'])->name('custom-receipt.print');
    });
});

