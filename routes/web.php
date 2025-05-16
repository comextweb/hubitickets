<?php

use App\Http\Controllers\AddOnController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\KnowledgebaseCategoryController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\NotificationTemplatesController;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\GoogleAuthenticationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketConversionController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\Auth\LoginExternalController;

require __DIR__ . '/auth.php';

Route::any('/cookie-consent', [SettingsController::class, 'CookieConsent'])->name('cookie-consent');
Route::get('/loginExternal', [LoginExternalController::class, 'handle']);

Route::controller(HomeController::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('home', 'store')->name('home.store');
    Route::get('search/{lang?}', 'search')->name('search');
    Route::post('search', 'ticketSearch')->name('ticket.search');
    Route::get('tickets/{id}', 'view')->name('home.view');
    Route::post('ticket/{id}', 'reply')->name('home.reply');
    Route::get('faq', 'faq')->name('faq');
    Route::get('knowledge', 'knowledge')->name('knowledge');
    Route::get('knowledgedesc/{id}', 'knowledgeDescription')->name('knowledgedesc');
    
});

Route::middleware(['web'])->group(function () {
    Route::post('/2faVerify', function () {
        return redirect(request()->get('2fa_referrer'));
    })->name('2faVerify')->middleware('2fa');
});

Route::name('admin.')->prefix('admin')->middleware(['auth', 'XSS'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // user routes
    Route::resource('users', UserController::class, ['names' => ['index' => 'users']]);

    // language routes
    Route::get('lang/clear', [LanguageController::class, 'clear'])->name('lang.clear');
    Route::get('lang/create', [LanguageController::class, 'create'])->name('lang.create');
    Route::post('lang/create/{lang?}/{module?}', [LanguageController::class, 'store'])->name('lang.store');
    Route::get('lang/{lang}/{module?}', [LanguageController::class, 'manageLanguage'])->name('lang.index');
    Route::post('lang/{lang}/{module?}', [LanguageController::class, 'storeData'])->name('lang.store.data');
    Route::get('lang-change/{lang}', [LanguageController::class, 'update'])->name('lang.update');
    Route::delete('lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');

    // category routes
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/{id}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');

    //Ticket Conversion Route

    Route::get('new-chat',[TicketConversionController::class,'index'])->name('new.chat');
    Route::get('get-all-ticket',[TicketConversionController::class,'getallTicket'])->name('get.all.tickets');

    Route::get('ticketdetail/{id}', [TicketConversionController::class, 'getticketDetails'])->name('ticketdetail');
    Route::get('ticket/{id}/status/change', [TicketConversionController::class, 'statusChange'])->name('ticket.status.change');
    Route::post('ticketreply/{id}', [TicketConversionController::class, 'replystore'])->name('reply.store');
    Route::post('ticketnote/{id}', [TicketConversionController::class, 'storeNote'])->name('tikcetnote.store');
    Route::get('ticket/{id}/assign/change', [TicketConversionController::class, 'assignChange'])->name('ticket.assign.change');
    Route::get('ticket/{id}/category/change', [TicketConversionController::class, 'categoryChange'])->name('ticket.category.change');
    Route::get('ticket/{id}/priority/change', [TicketConversionController::class, 'priorityChange'])->name('ticket.priority.change');
    Route::post('ticket/{id}/name/change', [TicketConversionController::class, 'ticketnameChange'])->name('ticket.name.change');
    Route::post('ticket/{id}/email/change', [TicketConversionController::class, 'ticketemailChange'])->name('ticket.email.change');
    Route::post('ticket/{id}/subject/change', [TicketConversionController::class, 'ticketsubChange'])->name('ticket.subject.change');
    Route::get('readmessge/{ticket_id}',[TicketConversionController::class,'readmessge'])->name('ticket.readmessge');
    Route::get('ticketcustomfield/show/{id}', [TicketConversionController::class, 'ticketcustomfield'])->name('ticketcustomfield.show');
    Route::post('ticketcustomfield/{id}/update', [TicketConversionController::class, 'ticketcustomfieldUpdate'])->name('ticketcustomfield.update');
        
    // custom field routes
    Route::resource('custom-field', CustomFieldController::class);
    Route::post('/custom-field/order', [CustomFieldController::class, 'order'])->name('custom-field.order');

    // ticket routes
    Route::get('ticket/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('ticket', [TicketController::class, 'store'])->name('tickets.store');
    Route::delete('ticket/{id}/destroy', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::delete('ticket-attachment/{tid}/destroy/{id}', [TicketController::class, 'attachmentDestroy'])->name('tickets.attachment.destroy');

    // faq routes
    Route::get('faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
    Route::get('faq', [FaqController::class, 'index'])->name('faq');
    Route::get('faq/{id}/edit', [FaqController::class, 'edit'])->name('faq.edit');
    Route::get('faq/show/{id}', [FaqController::class, 'show'])->name('show.faq');
    Route::delete('faq/{id}/destroy', [FaqController::class, 'destroy'])->name('faq.destroy');
    Route::post('faq/{id}/update', [FaqController::class, 'update'])->name('faq.update');

    Route::resource('priority', PriorityController::class);

    // Knowledgebase Routes
    Route::get('knowledge', [KnowledgeController::class, 'index'])->name('knowledge');
    Route::get('knowledge/create', [KnowledgeController::class, 'create'])->name('knowledge.create');
    Route::post('knowledge', [KnowledgeController::class, 'store'])->name('knowledge.store');
    Route::get('knowledge/{id}/edit', [KnowledgeController::class, 'edit'])->name('knowledge.edit');
    Route::delete('knowledge/{id}/destroy', [KnowledgeController::class, 'destroy'])->name('knowledge.destroy');
    Route::post('knowledge/{id}/update', [KnowledgeController::class, 'update'])->name('knowledge.update');
    Route::get('knowledge/show/{id}', [KnowledgeController::class, 'show'])->name('show.knowledgebase');

    // Knowledgebase category Routes
    Route::get('knowledgecategory', [KnowledgebaseCategoryController::class, 'index'])->name('knowledgecategory');
    Route::get('knowledgecategory/create', [KnowledgebaseCategoryController::class, 'create'])->name('knowledgecategory.create');
    Route::post('knowledgecategory', [KnowledgebaseCategoryController::class, 'store'])->name('knowledgecategory.store');
    Route::get('knowledgecategory/{id}/edit', [KnowledgebaseCategoryController::class, 'edit'])->name('knowledgecategory.edit');
    Route::delete('knowledgecategory/{id}/destroy', [KnowledgebaseCategoryController::class, 'destroy'])->name('knowledgecategory.destroy');
    Route::post('knowledgecategory/{id}/update', [KnowledgebaseCategoryController::class, 'update'])->name('knowledgecategory.update');

    // Setting Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::post('/email-settings', [SettingsController::class, 'emailSettingStore'])->name('email.settings.store');
    Route::post('/pusher-settings', [SettingsController::class, 'pusherSettingStore'])->name('pusher.settings.store');
    Route::post('/recaptcha-settings', [SettingsController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store');
    Route::post('/test', [SettingsController::class, 'testEmail'])->name('test.email');
    Route::post('/test/send', [SettingsController::class, 'testEmailSend'])->name('test.email.send');

    // Roles Route
    Route::get('roles',[RoleController::class,'index'])->name('roles');
    Route::get('role-create',[RoleController::class,'create'])->name('role.create');
    Route::post('role-store',[RoleController::class,'store'])->name('role.store');
    Route::get('role-edit/{roleId}',[RoleController::class,'edit'])->name('role.edit');
    Route::post('role-update/{roleId}',[RoleController::class,'update'])->name('role.update');
    Route::delete('role-delete/{roleId}',[RoleController::class,'destroy'])->name('role.delete');

    // AddOn Manager Page
    Route::get('addon/list',[AddOnController::class,'index'])->name('addon.list');
    Route::post('addon-enable', [AddOnController::class, 'addonEnable'])->name('addon.enable');
    Route::get('addon/add', [AddOnController::class, 'addAddOn'])->name('addon.add');
    Route::post('addon-install', [AddOnController::class, 'installAddon'])->name('addon.install');
});

Route::group(['middleware' => ['web', 'auth', 'verified','XSS']], function () {

    Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage');

    Route::any('users-reset-password/{id}', [UserController::class, 'userPassword'])->name('user.reset');
    Route::post('users-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');
    Route::get('user-login/{id}', [UserController::class, 'LoginManage'])->name('users.login');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/{id}', [UserController::class, 'editprofile'])->name('update.profile');
    Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');

    //====================================  User Log ====================================//
    Route::get('/user-log', [UserController::class, 'userlog'])->name('userlog');
    Route::delete('/user-log-delete/{id}', [UserController::class, 'userlogDestroy'])->name('userlog.destroy');
    Route::get('/view-user-log/{id}', [UserController::class, 'userlogview'])->name('userlog.display');

    Route::post('storage-settings', [SettingsController::class, 'storageSettingStore'])->name('storage.setting.store');
    Route::post('setting/seo', [SettingsController::class, 'saveSEOSettings'])->name('seo.settings');
    Route::post('cookie-setting', [SettingsController::class, 'saveCookieSettings'])->name('cookie.setting');
    Route::post('chatgptkey-setting', [SettingsController::class, 'chatgptkey'])->name('settings.chatgptkey');

    //====================================  Notification ====================================//
    Route::resource('notification-templates', NotificationTemplatesController::class);
    Route::get('notification-templates/{id?}/{lang?}/', [NotificationTemplatesController::class, 'index'])->name('notifications-templates.index');
    Route::get('notification_templates_lang/{id}/{lang?}', [NotificationTemplatesController::class, 'manageNotificationLang'])->name('manage.notification.language');

    //====================================  Email Notification ====================================//
    Route::resource('email_template', EmailTemplateController::class);
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language');
    Route::post('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language');
    Route::post('email_template_status', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language');

    //====================================  chatgpt ====================================//
    Route::get('generate/{template_name}', [AiTemplateController::class, 'create'])->name('generate');
    Route::post('generate/keywords/{id}', [AiTemplateController::class, 'getKeywords'])->name('generate.keywords');
    Route::post('generate/response', [AiTemplateController::class, 'AiGenerate'])->name('generate.response');
    Route::get('grammar/{template}', [AiTemplateController::class, 'grammar'])->name('grammar');
    Route::post('grammar/response', [AiTemplateController::class, 'grammarProcess'])->name('grammar.response');

    Route::get('export/tickets', [TicketController::class, 'export'])->name('tickets.export');

    //====================================  2FA Google Authenticated ====================================//
    Route::post('/generateSecret', [GoogleAuthenticationController::class, 'generate2faSecret'])->name('generate2faSecret');
    Route::post('/enable2fa', [GoogleAuthenticationController::class, 'enable2fa'])->name('enable2fa');
    Route::post('/disable2fa', [GoogleAuthenticationController::class, 'disable2fa'])->name('disable2fa');
});

// frontend page route
Route::get('get_ticket_message', [TicketConversionController::class, 'getMessage'])->name('get_ticket_message')->middleware(['XSS']);
Route::post('ticket_floating_message', [TicketConversionController::class, 'sendFloatingMessage'])->name('ticket_floating_message')->middleware(['XSS']);

Route::get('/config-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Clear Cache successfully.');
})->name('cache.clear');

//instgram & facebook webhook call
Route::any('/meta/callback', [MetaController::class, 'handleWebhook'])->name('meta.callback')->withoutMiddleware([VerifyCsrfToken::class]);


// Updater Routes
Route::group(['prefix' => 'update', 'as' => 'LaravelUpdater::', 'namespace' => 'RachidLaasri\LaravelInstaller\Controllers', 'middleware' => 'web'], function () {
    Route::group(['middleware' => 'updater'], function () {
        Route::get('/', [
            'as' => 'welcome',
            'uses' => 'UpdateController@welcome',
        ]);

        Route::get('overview', [
            'as' => 'overview',
            'uses' => 'UpdateController@overview',
        ]);

        Route::get('database', [
            'as' => 'database',
            'uses' => 'UpdateController@database',
        ]);
    });
    Route::get('final', [
        'as' => 'final',
        'uses' => 'UpdateController@finish',
    ]);
});


