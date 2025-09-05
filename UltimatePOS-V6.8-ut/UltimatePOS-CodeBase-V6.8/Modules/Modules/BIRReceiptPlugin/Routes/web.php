<?php

use Illuminate\Support\Facades\Route;
use Modules\BIRReceiptPlugin\Http\Controllers\BIRReceiptController;

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

Route::group(['prefix' => 'bir-receipt', 'middleware' => ['web', 'auth']], function () {
    
    // Main BIR Receipt routes
    Route::get('/', [BIRReceiptController::class, 'index'])->name('bir-receipt.index');
    Route::post('/generate', [BIRReceiptController::class, 'generateReceipt'])->name('bir-receipt.generate');
    Route::get('/templates', [BIRReceiptController::class, 'getTemplates'])->name('bir-receipt.templates');
    
    // Template customization routes
    Route::get('/customize/{templateCode}', [BIRReceiptController::class, 'customizeTemplate'])->name('bir-receipt.customize');
    Route::post('/customize/{templateCode}/save', [BIRReceiptController::class, 'saveCustomization'])->name('bir-receipt.save-customization');
    
    // Settings routes
    Route::get('/settings', [BIRReceiptController::class, 'showSettings'])->name('bir-receipt.settings');
    Route::post('/settings/save', [BIRReceiptController::class, 'saveSettings'])->name('bir-receipt.save-settings');
    
    // AJAX routes for dynamic functionality
    Route::post('/generate-number', function() {
        $controller = new BIRReceiptController();
        return response()->json(['receipt_number' => $controller->generateBIRReceiptNumber()]);
    })->name('bir-receipt.generate-number');
    
    // Preview routes
    Route::get('/preview/{templateCode}', [BIRReceiptController::class, 'generateReceipt'])->name('bir-receipt.preview');
    
});

// Public routes (for receipt generation without authentication)
Route::group(['prefix' => 'bir-receipt/public'], function () {
    Route::post('/generate', [BIRReceiptController::class, 'generateReceipt'])->name('bir-receipt.public.generate');
});
