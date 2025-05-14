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
use Workdo\Ratings\Http\Controllers\Company\SettingsController;
use Workdo\Ratings\Http\Controllers\TicketRatingController;

Route::group(['middleware' => ['web','auth','verified','ModuleCheckEnable:Ratings']], function () {
    Route::prefix('ratings')->group(function () {
        Route::post('/setting/store', [SettingsController::class,'store'])->name('ratings.setting.store');  
        Route::resource('rating', TicketRatingController::class);
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::prefix('ratings')->group(function () {
        Route::get('/ticket-rating/{ticket}', [TicketRatingController::class,'ratingPage'])->name('ticket.rating');                        
        Route::post('/ticket-rating-store', [TicketRatingController::class,'ratingStore'])->name('ticket.rating.store');                        
    });
});