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
use Workdo\Tags\Http\Controllers\TagsController;

Route::group(['middleware' => ['web','auth','verified','XSS','ModuleCheckEnable:Tags']], function () {
    Route::prefix('tags')->group(function () {
        Route::resource('tags', TagsController::class);
        Route::post('ticket/assign-tags/{id}', [TagsController::class, 'assignTags'])->name('ticket.assign.tags');
        Route::get('get-tags/{ticketId}', [TagsController::class, 'getTags'])->name('get.all.tags');
    });
});
