<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PicsiteController;
use App\Http\Controllers\PicRpkController;
use App\Http\Controllers\adminRegisController;
use App\Http\Controllers\picAdminController;
use App\Http\Controllers\picincidentController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
        Route::get('/ongoing-order', [LogisticController::class, 'ongoingOrderPage'])->name('ongoing-order');
        Route::get('/order/{transaction}/download', [LogisticController::class, 'downloadOrder']);
        Route::put('/order/{order}/reject', [LogisticController::class, 'rejectOrder']);
        Route::get('/report', [LogisticController::class, 'reportPage'])->name('report');
        Route::get('/history', [LogisticController::class, 'index'])->name('history');
        Route::get('/stocks', [LogisticController::class, 'stocksPage'])->name('stocks');
        Route::put('/stocks/{item}/edit', [LogisticController::class, 'editItem']);
        Route::post('/stocks', [LogisticController::class, 'storeItem'])->name('stocks');

        Route::post('/upload', [LogisticController::class, 'uploadItem']);
    });

    Route::prefix('picsite')->name('picsite.')->group(function(){
        Route::get('/rpk', [PicRpkController::class , 'rpk']);
        Route::post('/uploadrpk', [PicRpkController::class , 'uploadrpk'])->name('upload.uploadrpk');
        Route::get('/downloadrpk' , [PicRpkController::class, 'downloadrpk'])->name('downloadrpk');

        Route::get('/view', [PicsiteController::class , 'view']);

        Route::get('/upload', [PicsiteController::class , 'uploadform']);
        Route::post('/upload',[PicsiteController::class, 'uploadfile'])->name('upload.uploadFile');
    });

    Route::prefix('picadmin')->name('picadmin.')->group(function(){
        Route::get('/dana', 'picAdminController@checkform');
        Route::post('/dana/rejectdana',[picAdminController::class, 'reject']);
        Route::post('/dana/approvedana',[picAdminController::class, 'approve']);

        Route::get('/rpk', [picAdminController::class , 'checkrpk']);
        Route::post('/rpk/update-status',[picAdminController::class, 'approverpk']);
        Route::post('/rpk/rejectrpk',[picAdminController::class, 'rejectrpk']);
        Route::get('/download' , [picAdminController::class , 'download'])->name('download');
    });

    Route::prefix('picincident')->name('picincident.')->group(function(){
        Route::get('/formclaim', 'picincidentController@formclaim');
        Route::get('/spgr', 'picincidentController@spgr');
        Route::get('/history', 'picincidentController@formclaimhistory');
        Route::post('/formclaim/submitform', [picincidentController::class, 'submitformclaim']);
        Route::delete('/formclaim/destroy/{claim}', [picincidentController::class , 'destroy']);
       
    });
    Route::prefix('insurance')->name('insurance.')->group(function(){
        Route::get('/', 'picAdminController@checkform');
       
    });
});
require __DIR__.'/auth.php';

Route::get('/registeradmin' , [RegisteredUserController::class , 'view']);

