<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sales\CustomerController;
use App\Http\Controllers\Ajax\CustomerController as AjaxController;
use App\Http\Controllers\Sales\QuoteController;
use Illuminate\Support\Facades\Route;
use App\Models\Quote;

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

Route::get('/', [AuthenticatedSessionController::class, 'create'])
->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('sales')->group(function(){
        Route::name('sales.')->group(function(){
            Route::resource('customers', CustomerController::class);
            Route::resource('quotes', QuoteController::class);
        });
    });

    Route::prefix('ajax')->group(function(){
        Route::name('ajax.')->group(function(){
            Route::resource('customers', AjaxController::class);
        });
    });


    Route::get('/ajax/customers', [AjaxController::class, 'index']);

    Route::get('/test', function(){
        $quote = Quote::with('customer')->where('id', 1)->first();
        $lineItems = $quote->lineItems;
        $subTotal = $quote->getSubtotal();
        $amount = $quote->getAmount();

        return view('pdf', compact('quote', 'lineItems', 'subTotal', 'amount'));
    });

    
    Route::get('/download-pdf', [QuoteController::class, 'downloadPdf']);

});


require __DIR__.'/auth.php';
