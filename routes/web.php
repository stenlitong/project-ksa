<?php

use App\Http\Controllers\AdminPurchasingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\LogisticController;
// use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\SupervisorController;
use App\Models\Barge;
use App\Models\Tug;

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

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('crew')->name('crew.')->group(function(){
        Route::get('/task', [CrewController::class, 'taskPage'])->name('task');
        Route::get('/order', [CrewController::class, 'orderPage'])->name('order');
        Route::get('/order/{orderHeads}/accept', [CrewController::class, 'acceptOrder']);
        Route::post('/{user}/add-cart', [CrewController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [CrewController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [CrewController::class, 'submitOrder']);
    });

    Route::prefix('logistic')->name('logistic.')->group(function(){
        Route::get('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrderPage']);
        Route::post('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [LogisticController::class, 'rejectOrder']);
        Route::get('/history-out', [LogisticController::class, 'historyOutPage'])->name('historyOut');
        Route::get('/download-out', [LogisticController::class, 'downloadOut'])->name('downloadOut');
        Route::get('/history-in', [LogisticController::class, 'historyInPage'])->name('historyIn');
        Route::get('/download-in', [LogisticController::class, 'downloadIn'])->name('downloadIn');
        Route::get('/stocks', [LogisticController::class, 'stocksPage'])->name('stocks');
        Route::put('/stocks/{item}/edit', [LogisticController::class, 'editItem']);
        Route::delete('/stocks/{item}/delete', [LogisticController::class, 'deleteItem']);
        Route::post('/stocks', [LogisticController::class, 'storeItem'])->name('stocks');
        Route::get('/make-order', [LogisticController::class, 'makeOrderPage'])->name('makeOrder');
        Route::post('/{user}/add-cart', [LogisticController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [LogisticController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [LogisticController::class, 'submitOrder']);
        Route::get('/{orderHeads}/download-pr', [LogisticController::class, 'downloadPr']);
        Route::get('/stock-order/{orderHeads}/accept-order', [LogisticController::class, 'acceptStockOrder']);
        Route::get('/report', [LogisticController::class, 'reportPage'])->name('report');
        Route::get('/download-report', [LogisticController::class, 'downloadReport'])->name('downloadReport');

        Route::post('/upload', [LogisticController::class, 'uploadItem']);
    });

    Route::prefix('supervisor')->name('supervisor.')->group(function(){
        Route::get('/{orderHeads}/approve-order', [SupervisorController::class, 'approveOrder']);
        Route::put('/{orderHeads}/reject-order', [SupervisorController::class, 'rejectOrder']);
        Route::get('/{orderHeads}/download-pr', [SupervisorController::class, 'downloadPr']);
    });

    Route::prefix('purchasing')->name('purchasing.')->group(function(){
        Route::get('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrderPage']);
        Route::post('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [PurchasingController::class, 'rejectOrder']);
        Route::post('/{suppliers}/edit', [PurchasingController::class, 'editSupplier']);
        Route::get('/report', [PurchasingController::class, 'reportPage'])->name('report');
        Route::get('/{orderHeads}/download-po', [PurchasingController::class, 'downloadPo']);
    });

    // Route::prefix('admin-logistic')->name('adminLogistic.')->group(function(){
    //     Route::post('/add-item', [AdminLogisticController::class, 'addItem'])->name('addItem');
    //     Route::put('/{item}/edit', [AdminLogisticController::class, 'editItem']);
    //     Route::get('/create-order', [AdminLogisticController::class, 'preMakeOrderPage'])->name('preMakeOrderPage');
    //     Route::post('/create-order', [AdminLogisticController::class, 'submittedCabangPage'])->name('submittedCabangPage');
    //     Route::get('/create-order/{cabang}', [AdminLogisticController::class, 'createOrderFormPage']);
    //     Route::post('/create-order/{cabang}/add-cart', [AdminLogisticController::class, 'addItemToCart']);
    //     Route::delete('/create-order/{cabang}/{cart}/delete-cart', [AdminLogisticController::class, 'deleteItemFromCart']);
    //     Route::post('/create-order/{cabang}/{user}/submit-order', [AdminLogisticController::class, 'submitOrder']);
    //     Route::get('/history-out', [AdminLogisticController::class, 'historyOutPage'])->name('historyOut');
    //     Route::get('/history-in', [AdminLogisticController::class, 'historyInPage'])->name('historyIn');
    //     Route::get('/download-out', [AdminLogisticController::class, 'downloadOut'])->name('downloadOut');
    //     Route::get('/download-in', [AdminLogisticController::class, 'downloadIn'])->name('downloadIn');
    // });

    Route::prefix('admin-purchasing')->name('adminPurchasing.')->group(function(){
        Route::post('/add-supplier', [AdminPurchasingController::class, 'addSupplier'])->name('add-supplier');
        Route::put('/{suppliers}/edit', [AdminPurchasingController::class, 'editSupplier']);
        Route::get('/form-ap', [AdminPurchasingController::class, 'formApPage'])->name('formApPage');
    });
});

Route::get('/ksa-admin/register', function(){
    return view('auth.registerAdmin');
});

Route::get('/add-boat', function(){
    Tug::create([
        'tugName' => 'Tug A',
        'areaOperations' => 'Jakarta',
        'classification' => 'Kapal',
        'yearModel' => '2021',
        'status' => 'operational'
    ]);

    Barge::create([
        'bargeName' => 'Barge A',
        'size' => 300,
        'type' => 'Barge',
        'areaOperation' => 'Jakarta',
        'bargeYear' => '2021',
        'status' => 'operational'
    ]);

    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
