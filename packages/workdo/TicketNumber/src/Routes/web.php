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
use Workdo\TicketNumber\Http\Controllers\Company\SettingsController;

Route::group(['middleware' => ['web','auth','verified','ModuleCheckEnable:TicketNumber']], function () {
    Route::prefix('ticketnumber')->group(function () {
        Route::post('/setting/store', [SettingsController::class,'setting'])->name('ticket.number.setting.store');

    });
});
