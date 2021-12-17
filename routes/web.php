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
        // Dashboard Page
        Route::post('/change-branch', [CrewController::class, 'changeBranch'])->name('changeBranch');
        Route::get('/completed-order', [CrewController::class, 'completedOrder'])->name('completed-order');
        Route::get('/in-progress-order', [CrewController::class, 'inProgressOrder'])->name('in-progress-order');

        // Task Page
        Route::get('/task', [CrewController::class, 'taskPage'])->name('task');

        // Order Page
        Route::get('/order', [CrewController::class, 'orderPage'])->name('order');
        Route::get('/order/{orderHeads}/accept', [CrewController::class, 'acceptOrder']);
        Route::post('/{user}/add-cart', [CrewController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [CrewController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [CrewController::class, 'submitOrder']);
    });

    Route::prefix('logistic')->name('logistic.')->group(function(){
        // Dashboard Page
        Route::get('/in-progress-order', [LogisticController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/completed-order', [LogisticController::class, 'completedOrder'])->name('completed-order');
        Route::get('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrderPage']);
        Route::patch('/order/{orderHeads}/edit/{orderDetails}', [LogisticController::class, 'editAcceptedQuantity']);
        Route::post('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [LogisticController::class, 'rejectOrder']);
        
        // Goods In/Out Page
        Route::get('/history-out', [LogisticController::class, 'historyOutPage'])->name('historyOut');
        Route::get('/download-out', [LogisticController::class, 'downloadOut'])->name('downloadOut');
        Route::get('/history-in', [LogisticController::class, 'historyInPage'])->name('historyIn');
        Route::get('/download-in', [LogisticController::class, 'downloadIn'])->name('downloadIn');

        // Stocks Page
        Route::get('/stocks', [LogisticController::class, 'stocksPage'])->name('stocks');
        Route::post('/stocks/{items}/request', [LogisticController::class, 'requestStock']);

        // Request DO Page
        Route::get('/request-do', [LogisticController::class, 'requestDoPage'])->name('requestDo');
        Route::get('/request-do/{orderDos}/accept-do', [LogisticController::class, 'acceptDo']);
        Route::get('/request-do/{orderDos}/download', [LogisticController::class, 'downloadDo']);

        // Order Page
        Route::get('/make-order', [LogisticController::class, 'makeOrderPage'])->name('makeOrder');
        Route::post('/{user}/add-cart', [LogisticController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [LogisticController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [LogisticController::class, 'submitOrder']);
        Route::get('/{orderHeads}/download-pr', [LogisticController::class, 'downloadPr']);
        Route::get('/stock-order/{orderHeads}/accept-order', [LogisticController::class, 'acceptStockOrder']);

        // Report Page
        Route::get('/report', [LogisticController::class, 'reportPage'])->name('report');
        Route::get('/download-report', [LogisticController::class, 'downloadReport'])->name('downloadReport');

        // Route::post('/upload', [LogisticController::class, 'uploadItem']);
    });

    Route::prefix('supervisor')->name('supervisor.')->group(function(){
        // Dashboard Page
        Route::get('/completed-order', [SupervisorController::class, 'completedOrder'])->name('completed-order');
        Route::get('/in-progress-order', [SupervisorController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/{orderHeads}/approve-order', [SupervisorController::class, 'approveOrder']);
        Route::put('/{orderHeads}/reject-order', [SupervisorController::class, 'rejectOrder']);
        Route::get('/{orderHeads}/download-pr', [SupervisorController::class, 'downloadPr']);

        // Report Page
        Route::get('/report', [SupervisorController::class, 'reportsPage'])->name('report');
        Route::get('/report/download', [SupervisorController::class, 'downloadReport'])->name('downloadReport');

        // Goods In/Out Page
        Route::get('/goods-out', [SupervisorController::class, 'historyOut'])->name('historyOut');
        Route::get('/goods-in', [SupervisorController::class, 'historyIn'])->name('historyIn');
        Route::get('/goods-out/download', [SupervisorController::class, 'downloadOut'])->name('downloadOut');
        Route::get('/goods-in/download', [SupervisorController::class, 'downloadIn'])->name('downloadIn');

        // Stocks Page
        Route::get('/item-stocks', [SupervisorController::class, 'itemStock'])->name('itemStock');
        Route::post('/item-stocks', [SupervisorController::class, 'addItemStock']);
        Route::post('/item-stocks/{item}/edit-item', [SupervisorController::class, 'editItemStock']);
        Route::delete('/item-stocks/{item}/delete-item', [SupervisorController::class, 'deleteItemStock']);

        // DO Page
        Route::get('/approval-do', [SupervisorController::class, 'approvalDoPage'])->name('approvalDoPage');
        Route::get('/approval-do/{orderDos}/forward', [SupervisorController::class, 'forwardDo']);
        Route::get('/approval-do/{orderDos}/deny', [SupervisorController::class, 'denyDo']);
        Route::get('/approval-do/{orderDos}/approve', [SupervisorController::class, 'approveDo']);
        Route::get('/approval-do/{orderDos}/reject', [SupervisorController::class, 'rejectDo']);
        Route::get('/approval-do/{orderDos}/download', [SupervisorController::class, 'downloadDo']);
    });

    Route::prefix('purchasing')->name('purchasing.')->group(function(){
        // Dashboard Page
        Route::get('/completed-order/{branch}', [PurchasingController::class, 'completedOrder']);
        Route::get('/in-progress-order/{branch}', [PurchasingController::class, 'inProgressOrder']);
        Route::get('/dashboard/{branch}', [PurchasingController::class, 'branchDashboard']);
        Route::post('/{suppliers}/edit', [PurchasingController::class, 'editSupplier']);
        Route::get('/{orderHeads}/download-po', [PurchasingController::class, 'downloadPo']);

        // Approve Order page
        Route::get('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrderPage']);
        Route::get('/order/{orderHeads}/revise', [PurchasingController::class, 'approveOrderPage']);
        Route::patch('/order/{orderHeads}/{orderDetails}/edit', [PurchasingController::class, 'editPriceOrderDetail']);
        Route::post('/order/{orderHeads}/approve', [PurchasingController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/revise', [PurchasingController::class, 'reviseOrder']);
        Route::post('/order/{orderHeads}/reject', [PurchasingController::class, 'rejectOrder']);
        Route::patch('/order/{orderDetails}/drop', [PurchasingController::class, 'dropOrderDetail']);
        Route::get('/order/{orderHeads}/{orderDetails}/undo', [PurchasingController::class, 'undoDropOrderDetail']);
        
        // Report Page
        Route::get('/report', [PurchasingController::class, 'reportPage'])->name('report');
        Route::get('/report/{cabang}', [PurchasingController::class, 'reportPageBranch']);
        Route::get('/report/download/{cabang}', [PurchasingController::class, 'downloadReport']);

        // Report AP Page
        Route::get('/report-ap', [PurchasingController::class, 'reportApPage'])->name('reportAp');
        Route::get('/report-ap/{branch}', [PurchasingController::class, 'reportApPageBranch']);
        Route::get('/report-ap/{branch}/export', [PurchasingController::class, 'exportReportAp']);

        // Supplier Page
        Route::get('/supplier', [PurchasingController::class, 'supplierPage']);
        Route::post('/supplier', [PurchasingController::class, 'addSupplier']);
        Route::put('/supplier', [PurchasingController::class, 'editSupplierDetail']);
        Route::delete('/supplier', [PurchasingController::class, 'deleteSupplier']);
    });
    
    Route::prefix('purchasing-manager')->name('purchasingManager.')->group(function(){
        // Dashboard Page
        Route::get('/{orderHeads}/download-po', [PurchasingManagerController::class, 'downloadPo']);
        Route::get('/dashboard/{branch}', [PurchasingManagerController::class, 'branchDashboard']);
        Route::post('/{suppliers}/edit', [PurchasingManagerController::class, 'editSupplier']);
        Route::get('/completed-order/{branch}', [PurchasingManagerController::class, 'completedOrder']);
        Route::get('/in-progress-order/{branch}', [PurchasingManagerController::class, 'inProgressOrder']);
        
        // Approve Order Page
        // Route::get('/order/{orderHeads}/approve', [PurchasingManagerController::class, 'approveOrderPage']);
        Route::get('/order/{orderHeads}/order-detail', [PurchasingManagerController::class, 'approveOrderPage']);
        Route::post('/order/{orderHeads}/approve', [PurchasingManagerController::class, 'approveOrder']);
        Route::patch('/order/{orderHeads}/reject', [PurchasingManagerController::class, 'rejectOrder']);
        Route::patch('/{orderHeads}/revise-order', [PurchasingManagerController::class, 'reviseOrder']);
        Route::get('/{orderHeads}/finalize-order', [PurchasingManagerController::class, 'finalizeOrder']);

        // AP Page
        Route::get('/form-ap', [PurchasingManagerController::class, 'formApPage'])->name('formApPage');
        Route::get('/form-ap/{branch}', [PurchasingManagerController::class, 'formApPageBranch']);
        Route::post('/form-ap/download', [PurchasingManagerController::class, 'downloadFile']);
        Route::patch('/form-ap/approve', [PurchasingManagerController::class, 'approveDocument']);
        Route::patch('/form-ap/reject', [PurchasingManagerController::class, 'rejectDocument']);

        // Report PR Page
        Route::get('/checklist-pr', [PurchasingManagerController::class, 'checklistPrPage'])->name('checklistPrPage');
        Route::get('/checklist-pr/{branch}', [PurchasingManagerController::class, 'checklistPrPageBranch']);

        // Report PO Page
        Route::get('/report-po', [PurchasingManagerController::class, 'reportPage'])->name('reportPoPage');
        Route::get('/report-po/{branch}', [PurchasingManagerController::class, 'reportPageBranch']);
        Route::get('/report-po/download/{branch}', [PurchasingManagerController::class, 'downloadReport']);

        // Report AP Page
        Route::get('/report-ap', [PurchasingManagerController::class, 'reportApPage']);
        Route::get('/report-ap/{branch}', [PurchasingManagerController::class, 'reportApPageBranch']);
        Route::get('/report-ap/{branch}/export', [PurchasingManagerController::class, 'exportReportAp']);
    });

    Route::prefix('admin-purchasing')->name('adminPurchasing.')->group(function(){
        // AP Page
        Route::get('/form-ap/{branch}', [AdminPurchasingController::class, 'formApPageBranch']);
        Route::put('/form-ap/upload', [AdminPurchasingController::class, 'uploadFile']);
        Route::post('/form-ap/ap-detail', [AdminPurchasingController::class, 'saveApDetail']);
        Route::patch('/form-ap/close', [AdminPurchasingController::class, 'closeAp']);

        // Report AP Page
        Route::get('/report-ap', [AdminPurchasingController::class, 'reportApPage'])->name('reportAp');
        Route::get('/report-ap/{branch}', [AdminPurchasingController::class, 'reportApPageBranch']);
        Route::delete('/report-ap/{helper_cursor}/delete', [AdminPurchasingController::class, 'deleteApDetail']);
        Route::get('/report-ap/download/{branch}', [AdminPurchasingController::class, 'downloadReportAp']);

        // Route::get('/form-ap/{apList}/download', [AdminPurchasingController::class, 'downloadFile']);
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
