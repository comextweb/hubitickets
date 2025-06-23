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
use Workdo\Webhook\Http\Controllers\WebhookController;

Route::group(['middleware' => ['web','auth','verified','ModuleCheckEnable:Webhook']], function () {
    Route::prefix('webhook')->group(function () {
        Route::resource('/webhook', WebhookController::class);
    });
});
