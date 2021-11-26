<?php

use App\Http\Controllers\AdminPurchasingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\PurchasingManagerController;
use App\Http\Controllers\SupervisorController;
use App\Models\Barge;
use App\Models\OrderHead;
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

Route::group(['middleware' => ['PreventBackHistory', 'auth']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('crew')->name('crew.')->group(function(){
        Route::post('/change-branch', [CrewController::class, 'changeBranch'])->name('changeBranch');
        Route::get('/completed-order', [CrewController::class, 'completedOrder'])->name('completed-order');
        Route::get('/in-progress-order', [CrewController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/task', [CrewController::class, 'taskPage'])->name('task');
        Route::get('/order', [CrewController::class, 'orderPage'])->name('order');
        Route::get('/order/{orderHeads}/accept', [CrewController::class, 'acceptOrder']);
        Route::post('/{user}/add-cart', [CrewController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [CrewController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [CrewController::class, 'submitOrder']);
    });

    Route::prefix('logistic')->name('logistic.')->group(function(){
        Route::get('/in-progress-order', [LogisticController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/completed-order', [LogisticController::class, 'completedOrder'])->name('completed-order');
        Route::get('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrderPage']);
        Route::patch('/order/{orderHeads}/edit/{orderDetails}', [LogisticController::class, 'editAcceptedQuantity']);
        Route::post('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [LogisticController::class, 'rejectOrder']);
        Route::get('/history-out', [LogisticController::class, 'historyOutPage'])->name('historyOut');
        Route::get('/download-out', [LogisticController::class, 'downloadOut'])->name('downloadOut');
        Route::get('/history-in', [LogisticController::class, 'historyInPage'])->name('historyIn');
        Route::get('/download-in', [LogisticController::class, 'downloadIn'])->name('downloadIn');
        Route::get('/stocks', [LogisticController::class, 'stocksPage'])->name('stocks');
        Route::post('/stocks/{items}/request', [LogisticController::class, 'requestStock']);
        Route::get('/request-do', [LogisticController::class, 'requestDoPage'])->name('requestDo');
        Route::get('/request-do/{orderDos}/accept-do', [LogisticController::class, 'acceptDo']);
        Route::get('/request-do/{orderDos}/download', [LogisticController::class, 'downloadDo']);
        // ============================================= soon to be deleted, just for references ==============================================================
        // Route::put('/stocks/{item}/edit', [LogisticController::class, 'editItem']);
        // Route::delete('/stocks/{item}/delete', [LogisticController::class, 'deleteItem']);
        // Route::post('/stocks', [LogisticController::class, 'storeItem'])->name('stocks');
        // ============================================= soon to be deleted, just for references ==============================================================
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
        Route::get('/completed-order', [SupervisorController::class, 'completedOrder'])->name('completed-order');
        Route::get('/in-progress-order', [SupervisorController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/{orderHeads}/approve-order', [SupervisorController::class, 'approveOrder']);
        Route::put('/{orderHeads}/reject-order', [SupervisorController::class, 'rejectOrder']);
        Route::get('/{orderHeads}/download-pr', [SupervisorController::class, 'downloadPr']);
        Route::get('/report', [SupervisorController::class, 'reportsPage'])->name('report');
        Route::get('/report/download', [SupervisorController::class, 'downloadReport'])->name('downloadReport');
        Route::get('/goods-out', [SupervisorController::class, 'historyOut'])->name('historyOut');
        Route::get('/goods-in', [SupervisorController::class, 'historyIn'])->name('historyIn');
        Route::get('/goods-out/download', [SupervisorController::class, 'downloadOut'])->name('downloadOut');
        Route::get('/goods-in/download', [SupervisorController::class, 'downloadIn'])->name('downloadIn');
        Route::get('/item-stocks', [SupervisorController::class, 'itemStock'])->name('itemStock');
        Route::post('/item-stocks', [SupervisorController::class, 'addItemStock']);
        Route::post('/item-stocks/{item}/edit-item', [SupervisorController::class, 'editItemStock']);
        Route::delete('/item-stocks/{item}/delete-item', [SupervisorController::class, 'deleteItemStock']);
        Route::get('/approval-do', [SupervisorController::class, 'approvalDoPage'])->name('approvalDoPage');
        Route::get('/approval-do/{orderDos}/forward', [SupervisorController::class, 'forwardDo']);
        Route::get('/approval-do/{orderDos}/deny', [SupervisorController::class, 'denyDo']);
        Route::get('/approval-do/{orderDos}/approve', [SupervisorController::class, 'approveDo']);
        Route::get('/approval-do/{orderDos}/reject', [SupervisorController::class, 'rejectDo']);
        Route::get('/approval-do/{orderDos}/download', [SupervisorController::class, 'downloadDo']);
    });

    Route::prefix('purchasing')->name('purchasing.')->group(function(){
        Route::get('/completed-order/{branch}', [PurchasingController::class, 'completedOrder']);
        Route::get('/in-progress-order/{branch}', [PurchasingController::class, 'inProgressOrder']);
        Route::get('/dashboard/{branch}', [PurchasingController::class, 'branchDashboard']);
        Route::get('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrderPage']);
        Route::patch('/order/{orderHeads}/{orderDetails}/edit', [PurchasingController::class, 'editPriceOrderDetail']);
        Route::post('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [PurchasingController::class, 'rejectOrder']);
        Route::get('/{orderHeads}/download-po', [PurchasingController::class, 'downloadPo']);
        Route::post('/{suppliers}/edit', [PurchasingController::class, 'editSupplier']);
        Route::get('/report', [PurchasingController::class, 'reportPage'])->name('report');
        Route::get('/report/{cabang}', [PurchasingController::class, 'reportPageBranch']);
        Route::get('/report/download/{cabang}', [PurchasingController::class, 'downloadReport']);
        // Route::get('/form-ap', [PurchasingController::class, 'formApPage'])->name('form-ap');
        // Route::get('/form-ap/{apList}/download', [PurchasingController::class, 'downloadFile']);
        // Route::get('/form-ap/{apList}/approve', [PurchasingController::class, 'approveAp']);
        // Route::post('/form-ap/{apList}/reject', [PurchasingController::class, 'rejectAp']);
    });
    
    Route::prefix('purchasing-manager')->name('purchasingManager.')->group(function(){
        Route::get('/{orderHeads}/download-po', [PurchasingManagerController::class, 'downloadPo']);
        Route::get('/dashboard/{branch}', [PurchasingManagerController::class, 'branchDashboard']);
        Route::post('/{suppliers}/edit', [PurchasingManagerController::class, 'editSupplier']);
        Route::get('/completed-order/{branch}', [PurchasingManagerController::class, 'completedOrder']);
        Route::get('/in-progress-order/{branch}', [PurchasingManagerController::class, 'inProgressOrder']);
        Route::get('/order/{orderHeads}/approve', [PurchasingManagerController::class, 'approveOrderPage']);
        Route::post('/order/{orderHeads}/approve', [PurchasingManagerController::class, 'approveOrder']);
        Route::patch('/order/{orderHeads}/reject', [PurchasingManagerController::class, 'rejectOrder']);
        Route::get('/report', [PurchasingManagerController::class, 'reportPage'])->name('report');
        Route::get('/report/{cabang}', [PurchasingManagerController::class, 'reportPageBranch']);
        Route::get('/report/download/{cabang}', [PurchasingManagerController::class, 'downloadReport']);
    });

    Route::prefix('admin-purchasing')->name('adminPurchasing.')->group(function(){
        Route::post('/add-supplier', [AdminPurchasingController::class, 'addSupplier'])->name('add-supplier');
        Route::put('/{suppliers}/edit', [AdminPurchasingController::class, 'editSupplier']);
        Route::delete('/{suppliers}/delete', [AdminPurchasingController::class, 'deleteSupplier']);
        Route::get('/form-ap', [AdminPurchasingController::class, 'formApPage'])->name('formApPage');
        Route::get('/form-ap/{branch}', [AdminPurchasingController::class, 'formApPageBranch']);

        Route::put('{cabang}/form-ap/upload', [AdminPurchasingController::class, 'uploadFile']);
        Route::get('/form-ap/{apList}/download', [AdminPurchasingController::class, 'downloadFile']);
    });

});

// ================================================= Dev Route =======================================================
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
