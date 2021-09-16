<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrewController;

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
        Route::post('/order', [CrewController::class, 'storeOrder'])->name('order');
    });

});

require __DIR__.'/auth.php';
