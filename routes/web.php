<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentReceiptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('payments/{payment}/print', action: [PaymentReceiptController::class, 'printReceipt'])->name('payments.print');
Route::get('/print/bulk-receipts', [PaymentReceiptController::class, 'printBulkReceipts'])
    ->name('print.bulk.receipts');


// root
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
