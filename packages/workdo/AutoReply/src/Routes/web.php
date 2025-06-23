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
use Workdo\AutoReply\Http\Controllers\Company\SettingsController;

Route::group(['middleware' => ['web', 'auth', 'verified','ModuleCheckEnable:AutoReply']], function () {
    Route::prefix('auto-reply')->group(function () {
    Route::post('/setting',[SettingsController::class,'store'])->name('autoreply.setting.store');
        
    });
});