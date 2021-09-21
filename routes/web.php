<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\LogisticController;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('crew')->name('crew.')->group(function(){
        Route::get('/order', [CrewController::class, 'orderPage'])->name('order');
        Route::get('/task', [CrewController::class, 'taskPage'])->name('task');
        Route::delete('/{cart}/delete', [CrewController::class, 'deleteItemFromCart']);
        Route::post('/{user}/add-cart', [CrewController::class, 'addItemToCart']);
        Route::get('/{user}/submit-order', [CrewController::class, 'submitOrder']);
    });

    Route::prefix('logistic')->name('logistic.')->group(function(){
        Route::get('/order/{order}/approve', [LogisticController::class, 'approveOrderPage']);
        Route::post('/order/{order}/approve', [LogisticController::class, 'createTransaction']);
        // Route::get('/order/{transaction}/download', [LogisticController::class, 'downloadOrder']); // Still in the making
        Route::put('/order/{order}/reject', [LogisticController::class, 'rejectOrder']);
        Route::get('/report', [LogisticController::class, 'reportPage'])->name('report');
        Route::get('/history', [LogisticController::class, 'index'])->name('history');
        Route::get('/stocks', [LogisticController::class, 'stocksPage'])->name('stocks');
        Route::put('/stocks/{item}/edit', [LogisticController::class, 'editItem']);
        Route::post('/stocks', [LogisticController::class, 'storeItem'])->name('stocks');

        Route::post('/upload', [LogisticController::class, 'uploadItem']);
    });
});

require __DIR__.'/auth.php';
