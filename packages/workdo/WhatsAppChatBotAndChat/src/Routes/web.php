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

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Workdo\WhatsAppChatBotAndChat\Http\Controllers\Company\SettingsController;
use Workdo\WhatsAppChatBotAndChat\Http\Controllers\SendWhatsAppMessageController;
use Workdo\WhatsAppChatBotAndChat\Http\Controllers\WhatsappChatBotController;

Route::group(['middleware' => ['web', 'auth', 'verified', 'ModuleCheckEnable:WhatsAppChatBotAndChat']], function () {
    Route::prefix('whatsappchatbotandchat')->group(function () {
        Route::post('/setting/store', [SettingsController::class, 'setting'])->name('whatsappchatbot.setting.store');
        Route::any('send-close-ticket-msg/{ticketId}',[SendWhatsAppMessageController::class,'askForCloseTicket'])->name('ask.close.ticket');
    });
});

Route::any('whatsapp/webhook',[WhatsappChatBotController::class,'receiveMessages'])->withoutMiddleware(VerifyCsrfToken::class)->name('whatsapp.webhook')->middleware('web');
