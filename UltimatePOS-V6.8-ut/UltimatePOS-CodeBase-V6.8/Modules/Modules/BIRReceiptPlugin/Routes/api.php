<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\BIRReceiptPlugin\Http\Controllers\BIRReceiptController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'bir-receipt', 'middleware' => ['api']], function () {
    
    // API routes for BIR Receipt generation
    Route::post('/generate', [BIRReceiptController::class, 'generateReceipt'])->name('api.bir-receipt.generate');
    Route::get('/templates', [BIRReceiptController::class, 'getTemplates'])->name('api.bir-receipt.templates');
    Route::post('/generate-number', function() {
        $controller = new BIRReceiptController();
        return response()->json(['receipt_number' => $controller->generateBIRReceiptNumber()]);
    })->name('api.bir-receipt.generate-number');
    
    // Settings API
    Route::get('/settings', [BIRReceiptController::class, 'showSettings'])->name('api.bir-receipt.settings');
    Route::post('/settings/save', [BIRReceiptController::class, 'saveSettings'])->name('api.bir-receipt.save-settings');
    
    // Customization API
    Route::get('/customize/{templateCode}', [BIRReceiptController::class, 'customizeTemplate'])->name('api.bir-receipt.customize');
    Route::post('/customize/{templateCode}/save', [BIRReceiptController::class, 'saveCustomization'])->name('api.bir-receipt.save-customization');
    
});
