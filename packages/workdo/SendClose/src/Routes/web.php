<?php

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


use Illuminate\Support\Facades\Route;
use Workdo\SendClose\Http\Controllers\SendCloseController;

Route::group(['middleware' => ['web','auth','verified','ModuleCheckEnable:SendClose']], function () {
    Route::prefix('sendclose')->group(function () {
        Route::post('ticketsendclose/{ticket_id}', [SendCloseController::class, 'sendclose'])->name('ticket.sendclose');
    });
});
