<?php

use App\Http\Controllers\AdminOperationalController;
use App\Http\Controllers\AdminPurchasingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\PurchasingManagerController;
use App\Http\Controllers\SupervisorController;
use App\Models\Barge;
use App\Models\OrderHead;
use App\Models\Tug;
use App\Http\Controllers\PicsiteController;
use App\Http\Controllers\PicRpkController;
use App\Http\Controllers\adminRegisController;
use App\Http\Controllers\picAdminController;
use App\Http\Controllers\picincidentController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardAjaxController;

// ========================================================================== Message ===============================================================================================
// Apologizes for the bad code or we called it "spaghetti" code, because we are consists of 2 intern programmers who are still learning everything while doing our final semester
// We need to research for every single thing and crammed everything while building this project under 6 months without the help of senior/project manager/any other it department 
// (just pure 2 intern programmers) 
// So we need to find every information on the internet, including creating the logic flow -> making the database -> implementing it using laravel (instead of cool & flashy js 
// framework, coz we need to build this project asap) -> hosting to AWS/prod (also learn how to use EC2, load balancer, auto scaling, security group, route53, rds)
// We knew that our project is far from perfect, there are a lot of inconsistencies, no optimization, many bloated files around, also the ui is not good
// we hope you guys the best of luck and can make a better version of our own project ! 
// ===================================================================================================================================================================================

Route::group(['middleware' => ['auth', 'PreventBackHistory']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('crew')->name('crew.')->group(function(){
        // Dashboard Page
        Route::post('/change-branch', [CrewController::class, 'changeBranch'])->name('changeBranch');
        Route::get('/completed-order', [CrewController::class, 'completedOrder'])->name('completed-order');
        Route::get('/in-progress-order', [CrewController::class, 'inProgressOrder'])->name('in-progress-order');

        // Ajax
        Route::get('/refresh-dashboard', [DashboardAjaxController::class, 'crewRefreshDashboard'])->name('crewRefreshDashboard');
        Route::get('/refresh-dashboard-completed', [DashboardAjaxController::class, 'crewRefreshDashboardCompleted'])->name('crewRefreshDashboardCompleted');
        Route::get('/refresh-dashboard-in-progress', [DashboardAjaxController::class, 'crewRefreshDashboardInProgress'])->name('crewRefreshDashboardInProgress');

        // Task Page
        Route::post('/create-task', [CrewController::class, 'createTaskPost']);
        Route::get('/create-task', [CrewController::class, 'taskPage'])->name('createTask');
        Route::get('/create-task/detail', [CrewController::class, 'createTaskDetailPage'])->name('taskDetail');

        // Ongoing Task Page
        Route::post('/ongoing-task', [CrewController::class, 'updateOngoingTask']);
        Route::patch('/ongoing-task', [CrewController::class, 'finalizeOngoingTask']);
        Route::patch('/ongoing-task/return-cargo', [CrewController::class, 'continueReturnCargo']);
        Route::delete('/ongoing-task', [CrewController::class, 'cancelOngoingTask']);
        Route::get('/ongoing-task', [CrewController::class, 'ongoingTaskPage'])->name('ongoingTaskPage');

        // Order Page
        Route::get('/order', [CrewController::class, 'orderPage'])->name('order');
        Route::get('/order/{orderHeads}/accept', [CrewController::class, 'acceptOrder']);
        Route::post('/{user}/add-cart', [CrewController::class, 'addItemToCart']);
        Route::delete('/{cart}/delete', [CrewController::class, 'deleteItemFromCart']);
        Route::post('/{user}/submit-order', [CrewController::class, 'submitOrder']);
    });

    Route::prefix('admin-operational')->name('adminOperational.')->group(function(){
        // Daily Reports Page
        Route::get('/daily-reports', [AdminOperationalController::class, 'reportTranshipmentPage'])->name('reportTranshipment');
        Route::post('/daily-reports', [AdminOperationalController::class, 'searchDailyReports'])->name('searchDailyReports');
        Route::post('/daily-reports/download', [AdminOperationalController::class, 'downloadDailyReports']);

        // Monitoring Page
        Route::get('/monitoring', [AdminOperationalController::class, 'monitoringPage'])->name('monitoring');
        Route::post('/search-monitoring', [AdminOperationalController::class, 'searchMonitoring'])->name('searchMonitoring');

        // Add Tugboat Page
        Route::get('/add-tugboat', [AdminOperationalController::class, 'addTugboatPage'])->name('addTugboat');
        Route::post('/add-tugboat', [AdminOperationalController::class, 'searchTugboat'])->name('searchTugboat');
        Route::patch('/add-tugboat', [AdminOperationalController::class, 'paginationTugBoat'])->name('paginationTugboat');
        Route::post('/add-newtugboat', [AdminOperationalController::class, 'addNewTugboat'])->name('addNewTugboat');
        Route::delete('/delete-tugboat', [AdminOperationalController::class, 'deleteTugboat']);

        // Add Barge Page
        Route::get('/add-barge', [AdminOperationalController::class, 'addBargePage'])->name('addBarge');
        Route::post('/add-barge', [AdminOperationalController::class, 'searchBarge'])->name('searchBarge');
        Route::patch('/add-barge', [AdminOperationalController::class, 'paginationBarge'])->name('paginationBarge');
        Route::post('/add-newbarge', [AdminOperationalController::class, 'addNewBarge'])->name('addNewBarge');
        Route::delete('/delete-barge', [AdminOperationalController::class, 'deleteBarge']);

    });

    Route::prefix('logistic')->name('logistic.')->group(function(){
        // Dashboard Page
        Route::get('/in-progress-order', [LogisticController::class, 'inProgressOrder'])->name('in-progress-order');
        Route::get('/completed-order', [LogisticController::class, 'completedOrder'])->name('completed-order');
        Route::get('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrderPage']);
        Route::patch('/order/{orderHeads}/edit/{orderDetails}', [LogisticController::class, 'editAcceptedQuantity']);
        Route::post('/order/{orderHeads}/approve', [LogisticController::class, 'approveOrder']);
        Route::post('/order/{orderHeads}/reject', [LogisticController::class, 'rejectOrder']);
        
        // Ajax
        Route::post('/refresh-logistic-dashboard', [DashboardAjaxController::class, 'logisticRefreshDashboard'])->name('logisticRefreshDashboard');
        Route::post('/refresh-logistic-dashboard-completed', [DashboardAjaxController::class, 'logisticRefreshDashboardCompleted'])->name('logisticRefreshDashboardCompleted');
        Route::post('/refresh-logistic-dashboard-in-progress', [DashboardAjaxController::class, 'logisticRefreshDashboardInProgress'])->name('logisticRefreshDashboardInProgress');

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

        // Ajax 
        Route::get('/refresh-request-do', [DashboardAjaxController::class, 'logisticRefreshOngoingDOPage'])->name('logisticRefreshOngoingDOPage');

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

        // Ajax
        Route::post('/refresh-supervisor-dashboard', [DashboardAjaxController::class, 'supervisorRefreshDashboard'])->name('supervisorRefreshDashboard');
        Route::post('/refresh-supervisor-dashboard-completed', [DashboardAjaxController::class, 'supervisorRefreshDashboardCompleted'])->name('supervisorRefreshDashboardCompleted');
        Route::post('/refresh-supervisor-dashboard-in-progress', [DashboardAjaxController::class, 'supervisorRefreshDashboardInProgress'])->name('supervisorRefreshDashboardInProgress');

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

        // Ajax
        Route::get('/refresh-approval-do', [DashboardAjaxController::class, 'supervisorRefreshApprovalDO'])->name('supervisorRefreshApprovalDO');
    });

    Route::prefix('purchasing')->name('purchasing.')->group(function(){
        // Dashboard Page
        Route::get('/completed-order/{branch}', [PurchasingController::class, 'completedOrder']);
        Route::get('/in-progress-order/{branch}', [PurchasingController::class, 'inProgressOrder']);
        Route::get('/dashboard/{branch}', [PurchasingController::class, 'branchDashboard']);
        Route::post('/{suppliers}/edit', [PurchasingController::class, 'editSupplier']);
        Route::get('/{orderHeads}/download-po', [PurchasingController::class, 'downloadPo']);

        // Ajax
        Route::post('/refresh-purchasing-dashboard', [DashboardAjaxController::class, 'purchasingRefreshDashboard'])->name('purchasingRefreshDashboard');
        Route::post('/refresh-purchasing-dashboard-completed', [DashboardAjaxController::class, 'purchasingRefreshDashboardCompleted'])->name('purchasingRefreshDashboardCompleted');
        Route::post('/refresh-purchasing-dashboard-in-progress', [DashboardAjaxController::class, 'purchasingRefreshDashboardInProgress'])->name('purchasingRefreshDashboardInProgress');

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
        
        // Ajax
        Route::post('/refresh-purchasing-manager-dashboard', [DashboardAjaxController::class, 'purchasingManagerRefreshDashboard'])->name('purchasingManagerRefreshDashboard');
        Route::post('/refresh-purchasing-manager-dashboard-completed', [DashboardAjaxController::class, 'purchasingManagerRefreshDashboardCompleted'])->name('purchasingManagerRefreshDashboardCompleted');
        Route::post('/refresh-purchasing-manager-dashboard-in-progress', [DashboardAjaxController::class, 'purchasingManagerRefreshDashboardInProgress'])->name('purchasingManagerRefreshDashboardInProgress');

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

        // Ajax
        Route::post('/refresh-form-ap', [DashboardAjaxController::class, 'purchasingManagerRefreshFormAp'])->name('purchasingManagerRefreshFormAp');

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

        // Ajax
        Route::post('/refresh-dashboard-admin-purchasing', [DashboardAjaxController::class, 'adminPurchasingRefreshFormAp'])->name('adminPurchasingRefreshFormAp');

        // Report AP Page
        Route::get('/report-ap', [AdminPurchasingController::class, 'reportApPage'])->name('reportAp');
        Route::get('/report-ap/{branch}', [AdminPurchasingController::class, 'reportApPageBranch']);
        Route::delete('/report-ap/{helper_cursor}/delete', [AdminPurchasingController::class, 'deleteApDetail']);
        Route::get('/report-ap/download/{branch}', [AdminPurchasingController::class, 'downloadReportAp']);

        // Ajax
        Route::post('/refresh-admin-purchasing-report-ap', [DashboardAjaxController::class, 'adminPurchasingRefreshReportAp'])->name('adminPurchasingRefreshReportAp');

        // Route::get('/form-ap/{apList}/download', [AdminPurchasingController::class, 'downloadFile']);
    });

    Route::prefix('picsite')->name('picsite.')->group(function(){
        Route::get('/rpk', [PicRpkController::class , 'rpk']);
        Route::post('/uploadrpk', [PicRpkController::class , 'uploadrpk'])->name('upload.uploadrpk');
        Route::get('/downloadrpk' , [PicRpkController::class, 'downloadrpk'])->name('downloadrpk');

        // Route::get('/view', [PicsiteController::class , 'view']);

        Route::get('/upload', [PicsiteController::class , 'uploadform']);
        Route::post('/upload',[PicsiteController::class, 'uploadfile'])->name('upload.uploadFile');
    });

    Route::prefix('picadmin')->name('picadmin.')->group(function(){
        Route::get('/dana', 'picAdminController@checkform');
        Route::post('/dana/rejectdana',[picAdminController::class, 'reject']);
        Route::post('/dana/approvedana',[picAdminController::class, 'approve']);
        
        Route::post('/dana/view',[picAdminController::class, 'view']);
        Route::post('/rpk/view',[picAdminController::class, 'viewrpk']);

        Route::get('/rpk', [picAdminController::class , 'checkrpk']);
        Route::post('/rpk/update-status',[picAdminController::class, 'approverpk']);
        Route::post('/rpk/rejectrpk',[picAdminController::class, 'rejectrpk']);
        Route::get('/download' , [picAdminController::class , 'download'])->name('download');
    });

    Route::prefix('picincident')->name('picincident.')->group(function(){
        Route::get('/formclaim', 'picincidentController@formclaim');
        Route::post('/formclaim/submitform', [picincidentController::class, 'submitformclaim']);
        Route::delete('/formclaim/destroy/{temp}', [picincidentController::class , 'destroy']);
        
        
        Route::post('/create-history', 'picincidentController@createformclaim');
        Route::delete('/history/destroy/{claims}', [picincidentController::class , 'DestroyExcel']);
        Route::get('/history', 'picincidentController@formclaimhistory');
        Route::get('/formclaimDownload', 'picincidentController@export');
        
        Route::get('/spgr', 'picincidentController@spgr');
        Route::post('/uploadSPGR', [picincidentController::class,'spgrupload']);

        Route::get('/NoteSpgr', 'picincidentController@notespgr');
        Route::post('/addNoteSpgr', 'picincidentController@uploadnotespgr');
        Route::delete('/NoteSpgr/destroy/{UpNotes}', [picincidentController::class , 'destroynote']);
        Route::put('/NoteSpgr/update/{UpNotes}', [picincidentController::class, 'updatenote']);

    });

    Route::prefix('insurance')->name('insurance.')->group(function(){
        Route::get('/CheckSpgr', 'InsuranceController@checkspgr');
        Route::post('/approvespgr',[InsuranceController::class, 'approvespgr']);
        Route::post('/rejectspgr',[InsuranceController::class, 'rejectspgr']);
        Route::post('/viewspgr',[InsuranceController::class, 'viewspgr']);

        Route::get('/HistoryNoteSpgr', 'InsuranceController@historynotespgr');
       
    });
});

Route::get('/', function () {
    return view('welcome');
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