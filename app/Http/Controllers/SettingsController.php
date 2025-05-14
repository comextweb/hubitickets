<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Mail\EmailTest;
use App\Models\EmailTemplate;
use App\Models\NotificationTemplates;
use App\Models\Setting;
use App\Models\Settings;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Artisan;
use Illuminate\Support\Facades\Validator;
use Workdo\Webhook\Entities\Webhook;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAbleTo('settings manage')) {
            $customFields = CustomField::orderBy('order')->get();
            $setting      = getCompanyAllSettings();
            $timezones               = config('timezones');
            $file_type = config('files_types');
            $google_recaptcha_version = ['v2-checkbox' => __('v2'), 'v3' => __('v3')];
            $EmailTemplates = NotificationTemplates::all();

            $activatedModules = getActiveModules();
            $email_notification_modules = NotificationTemplates::where('type','mail')->whereIn('module', $activatedModules)->orwhere('module','General')->pluck('module')->toArray();
            $email_notification_modules = array_unique($email_notification_modules);

            $email_notify = NotificationTemplates::where('type', 'mail')->whereIn('module', $email_notification_modules)->get(['module', 'action']);

            return view('admin.users.setting', compact('customFields', 'setting', 'timezones', 'file_type', 'google_recaptcha_version', 'EmailTemplates' , 'email_notification_modules' , 'email_notify'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function getEmailSection($settings){
        return view('admin.email-setting.index',compact('settings'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $post = $request->all();
        $companySetting = getCompanyAllSettings();
        unset($post['_token']);

        if ($user->isAbleTo('settings manage')) {

            $request->validate([
                'app_name' => 'required|string',
                'footer_text' => 'required|string',
                'default_language' => 'required',
            ]);

            if ($request->hasFile('favicon')) {

                $request->validate(
                    [
                        'favicon' => 'image',
                    ]
                );
                $favicon = 'favicon_' . time() . '.png';
                $dir = 'logo';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = uploadFile($request, 'favicon', $favicon, $dir, $validation);
                $oldFavicon = isset($companySetting['favicon']) ? $companySetting['favicon'] : '';

                if (!empty($oldFavicon) && checkFile($oldFavicon)) {
                    deleteFile($oldFavicon);
                }
                if ($path['flag'] == 1) {
                    $favicon = $path['url'];
                    $post['favicon'] = $favicon;
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
            if ($request->hasFile('dark_logo')) {

                $request->validate(
                    [
                        'dark_logo' => 'image',
                    ]
                );
                $logoName = 'logo-dark_' . time() . '.png';
                $dir = 'logo';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = uploadFile($request, 'dark_logo', $logoName, $dir, $validation);
                $oldDarkLogo = isset($companySetting['dark_logo']) ? $companySetting['dark_logo'] : '';

                if (!empty($oldDarkLogo) && checkFile($oldDarkLogo)) {
                    deleteFile($oldDarkLogo);
                }

                if ($path['flag'] == 1) {
                    $logo = $path['url'];
                    $post['dark_logo'] = $logo;
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->hasFile('light_logo')) {

                $request->validate(
                    [
                        'light_logo' => 'image',
                    ]
                );
                $lightlogoName = 'logo-light_ ' . time() . '.png';
                $dir = 'logo';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = uploadFile($request, 'light_logo', $lightlogoName,  $dir, $validation);
                $oldLightLogo = isset($companySetting['light_logo']) ? $companySetting['light_logo'] : '';

                if (!empty($oldLightLogo) && checkFile($oldLightLogo)) {
                    deleteFile($oldLightLogo);
                }
                if ($path['flag'] == 1) {
                    $white_logo = $path['url'];
                    $post['light_logo'] = $white_logo;
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if (!isset($post['timezone'])) {
                $post['timezone'] = $request->timezone;
            }

            if (!isset($post['app_name'])) {
                $post['app_name'] = $request->app_name;
            }

            if (!isset($post['footer_text'])) {
                $post['footer_text'] = $request->footer_text;
            }

            if (!isset($post['default_language'])) {
                $post['default_language'] = $request->default_language;
            }

            if (!isset($post['site_rtl'])) {
                $post['site_rtl'] = 'off';
            }

            if (!isset($post['faq'])) {
                $post['faq'] = 'off';
            }

            if (!isset($post['knowledge_base'])) {
                $post['knowledge_base'] = 'off';
            }

            if (isset($request->color) && $request->color_flag == 'false') {
                $post['color'] = $request->color;
            } else {
                $post['color'] = $request->custom_color;
            }

            if (!isset($post['cust_theme_bg'])) {
                $post['cust_theme_bg'] = 'off';
            }

            if (!isset($post['cust_darklayout'])) {
                $post['cust_darklayout'] = 'off';
            }



            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];
                Settings::updateOrInsert($data, ['value' => $value]);
            }
            companySettingCacheForget(creatorId());
            return redirect()->back()->with('success', __('Setting updated successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    public function emailSettingStore(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('settings manage')) {
            $post = $request->all();
            unset($post['_token']);
            $rules = [
                'mail_driver' => 'required|string|max:50',
                'mail_host' => 'required|string|max:50',
                'mail_port' => 'required|string|max:50',
                'mail_username' => 'required|string|max:50',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'required|string|max:50',
                'mail_from_address' => 'required|string|max:50',
                'mail_from_name' => 'required|string|max:50',
            ];

            $validator = Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];

                Settings::updateOrInsert($data, ['value' => $value]);
            }
            companySettingCacheForget(creatorId());
            return redirect()->back()->with('success', __('Email Settings updated successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }



    public function recaptchaSettingStore(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('settings manage')) {
            $rules = [];

            if ($request->recaptcha_module == 'yes') {
                $rules['google_recaptcha_key'] = 'required';
                $rules['google_recaptcha_secret'] = 'required';
                $rules['google_recaptcha_version'] = 'required';
            }

            $validator = Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if (!isset($post['RECAPTCHA_MODULE'])) {
                $post['RECAPTCHA_MODULE'] = $request->recaptcha_module  ?? 'no';
            }

            if (!isset($post['NOCAPTCHA_SITEKEY'])) {
                $post['NOCAPTCHA_SITEKEY'] = $request->google_recaptcha_key ?? '';
            }

            if (!isset($post['NOCAPTCHA_SECRET'])) {
                $post['NOCAPTCHA_SECRET'] = $request->google_recaptcha_secret ?? '';
            }

            if (!isset($post['google_recaptcha_version'])) {
                $post['google_recaptcha_version'] = $request->google_recaptcha_version ?? '';
            }

            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];
                Settings::updateOrInsert($data, ['value' => $value]);
            }
            companySettingCacheForget(creatorId());
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function pusherSettingStore(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('settings manage')) {
            $rules = [];

            if ($request->enable_chat == 'yes') {
                $rules['pusher_app_id']      = 'required';
                $rules['pusher_app_key']     = 'required';
                $rules['pusher_app_secret']  = 'required';
                $rules['pusher_app_cluster'] = 'required';
            }

            $validator = Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $post = [];

            if (!isset($post['CHAT_MODULE'])) {
                $post['CHAT_MODULE'] = $request->has('enable_chat') ? 'yes' : 'no';
            }

            if (!isset($post['PUSHER_APP_ID'])) {
                $post['PUSHER_APP_ID'] = $request->has('pusher_app_id') ? $request->pusher_app_id : '';
            }

            if (!isset($post['PUSHER_APP_KEY'])) {
                $post['PUSHER_APP_KEY'] = $request->has('pusher_app_key') ? $request->pusher_app_key : '';
            }

            if (!isset($post['PUSHER_APP_SECRET'])) {
                $post['PUSHER_APP_SECRET'] = $request->has('pusher_app_secret') ? $request->pusher_app_secret : '';
            }


            if (!isset($post['PUSHER_APP_CLUSTER'])) {
                $post['PUSHER_APP_CLUSTER'] = $request->has('pusher_app_cluster') ? $request->pusher_app_cluster : '';
            }

            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];
                Settings::updateOrInsert($data, ['value' => $value]);
            }
            companySettingCacheForget(creatorId());
            return redirect()->back()->with('success', __('Pusher Settings Save Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function testEmail(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('settings manage')) {
            $data                      = [];
            $data['mail_driver']       = $request->mail_driver;
            $data['mail_host']         = $request->mail_host;
            $data['mail_port']         = $request->mail_port;
            $data['mail_username']     = $request->mail_username;
            $data['mail_password']     = $request->mail_password;
            $data['mail_encryption']   = $request->mail_encryption;
            $data['mail_from_address'] = $request->mail_from_address;
            $data['mail_from_name']    = $request->mail_from_name;

            return view('admin.users.test_email', compact('data'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function testEmailSend(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'mail_driver' => 'required',
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_from_address' => 'required',
                'mail_from_name' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            Mail::to($request->email)->send(new EmailTest());
        } catch (\Exception $e) {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }

        return response()->json(
            [
                'is_success' => true,
                'message' => __('Email send Successfully'),
            ]
        );
    }



    public function storageSettingStore(Request $request)
    {

        if (isset($request->storage_setting) && $request->storage_setting == 'local') {
            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 's3') {
            $request->validate(
                [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 'wasabi') {
            $request->validate(
                [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }


        foreach ($post as $key => $value) {
            $data = [
                'name' => $key,
                "created_by" => creatorId(),
            ];
            Settings::updateOrInsert($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());

        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }


    public function saveSEOSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'meta_keywords' => 'required',
                'meta_description' => 'required',
                'meta_image' => 'required|image',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $settings = getCompanyAllSettings();
        if ($request->hasFile('meta_image')) {
            $validation = [
                'mimes:' . 'png',
                'max:' . '20480',
            ];

            $imageNameToStore = 'meta_' . time() . '_image' . '.png';
            $path = uploadFile($request, 'meta_image', $imageNameToStore, 'metaevent', $validation);

            $oldMetaImage = isset($settings['meta_image']) ? $settings['meta_image'] : '';
            if (!empty($oldMetaImage) && checkFile($oldMetaImage)) {
                deleteFile($oldMetaImage);
            }

            if ($path['flag'] == 1) {
                $metaImage = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['meta_image']  = $metaImage;
        }

        $post['meta_keywords']            = $request->meta_keywords;
        $post['meta_description']            = $request->meta_description;

        foreach ($post as $key => $value) {
            $data = [
                'name' => $key,
                'created_by' => creatorId(),
            ];
            Settings::updateOrInsert($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());
        return redirect()->back()->with('success', 'SEO setting successfully updated.');
    }



    public function slack(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'slack_webhook' => 'required',
            ]
        );

        $post = [];
        $post['slack_webhook'] = $request->has('slack_webhook') ? $request->slack_webhook : '';
        $post['user_notification'] = $request->has('user_notification') ? $request->input('user_notification') : 0;
        $post['ticket_notification'] = $request->has('ticket_notification') ? $request->input('ticket_notification') : 0;
        $post['reply_notification'] = $request->has('reply_notification') ? $request->input('reply_notification') : 0;

        foreach ($post as $key => $value) {
            $data = [
                'name' => $key,
                'created_by' => creatorId(),
            ];

            Settings::updateOrInsert($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());

        return redirect()->back()->with('success', 'Slack Settings Save Successfully.');
    }

    public function telegram(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'telegram_accestoken' => 'required',
                'telegram_chatid' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $post = [];
        $post['telegram_accestoken'] = $request->has('telegram_accestoken') ? $request->telegram_accestoken : '';
        $post['telegram_chatid'] = $request->has('telegram_chatid') ? $request->telegram_chatid : '';
        $post['telegram_user_notification'] = $request->has('telegram_user_notification') ? $request->input('telegram_user_notification') : 0;
        $post['telegram_ticket_notification'] = $request->has('telegram_ticket_notification') ? $request->input('telegram_ticket_notification') : 0;
        $post['telegram_reply_notification'] = $request->has('telegram_reply_notification') ? $request->input('telegram_reply_notification') : 0;


        foreach ($post as $key => $value) {
            $data = [
                'name' => $key,
                'created_by' => creatorId(),
            ];
            Settings::updateOrCreate($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());
        return redirect()->back()->with('success', __('Telegram Settings Save Successfully.'));
    }



    public function chatgptkey(Request $request)
    {
        if (Auth::user()->isAbleTo('settings manage')){
            $user = Auth::user();
            $validator = Validator::make(
                $request->all(),
                [
                    'chatgpt_key' => 'required',
                    'chat_gpt_model' => 'required',

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post = $request->all();
            unset($post['_token']);
            if (!isset($post['is_enabled'])) {
                $post['is_enabled'] =  $request->is_enabled ?? 'off';
            }
            $post['chatgpt_key'] = $request->chatgpt_key;
            $post['chat_gpt_model'] = $request->chat_gpt_model;

            foreach ($post as $key => $value) {
                $data = [
                    'name' => $key,
                    'created_by' => creatorId(),
                ];
                Settings::updateOrInsert($data, ['value' => $value]);
            }
            companySettingCacheForget(creatorId());
            return redirect()->back()->with('success', __('Chatgpykey Setting Saved Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function saveCookieSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_description' => 'required',
                'contactus_url' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $post = $request->all();
        unset($post['_token']);


        if (!isset($post['enable_cookie'])) {
            $post['enable_cookie'] = $request->enable_cookie ?? 'off';
        }

        if (!isset($post['cookie_logging'])) {
            $post['cookie_logging'] = $request->cookie_logging ?? 'off';
        }

        $post['cookie_title']            = $request->cookie_title;
        $post['cookie_description']            = $request->cookie_description;
        $post['strictly_cookie_title']            = $request->strictly_cookie_title;
        $post['strictly_cookie_description']            = $request->strictly_cookie_description;
        $post['more_information_description']            = $request->more_information_description;
        $post['contactus_url']            = $request->contactus_url;

        foreach ($post as $key => $value) {
            $data = [
                'name' => $key,
                'created_by' => creatorId(),
            ];
            Settings::updateOrInsert($data, ['value' => $value]);
        }
        companySettingCacheForget(creatorId());
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function CookieConsent(Request $request)
    {
        $settings = getCompanyAllSettings();
        if ($request['cookie']) {
            if ($settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
                $allowed_levels = ['necessary', 'analytics', 'targeting'];
                $levels = array_filter($request['cookie'], function ($level) use ($allowed_levels) {
                    return in_array($level, $allowed_levels);
                });
                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                // Generate new CSV line
                $browser_name = $whichbrowser->browser->name ?? null;
                $os_name = $whichbrowser->os->name ?? null;
                $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                $ip = '49.36.83.154';
                $ip = $_SERVER['REMOTE_ADDR']; // your ip address hered
                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
                $date = (new \DateTime())->format('Y-m-d');
                $time = (new \DateTime())->format('H:i:s') . ' UTC';
                $new_line = implode(',', [$ip, $date, $time, json_encode($request['cookie']), $device_type, $browser_language, $browser_name, $os_name, isset($query) && isset($query['country']) ? $query['country'] : '', isset($query) && isset($query['region']) ? $query['region'] : '', isset($query) && isset($query['regionName']) ? $query['regionName'] : '', isset($query) && isset($query['city']) ? $query['city'] : '', isset($query) && isset($query['zip']) ? $query['zip'] : '', isset($query) && isset($query['lat']) ? $query['lat'] : '', isset($query) && isset($query['lon']) ? $query['lon'] : '']);
                $uploadPath = base_path('uploads/cookie/data.csv');
                $uploadDir = dirname($uploadPath);
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
                }
                // Check if the file exists, and create it if necessary
                if (!file_exists($uploadPath)) {
                    $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name';
                    file_put_contents($uploadPath, $first_line . PHP_EOL, FILE_APPEND | LOCK_EX); // Write the first line
                }
                // Append new content to the file
                file_put_contents($uploadPath, $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);
                return response()->json('success');
            }
            return response()->json('error');
        } else {
            return redirect()->back();
        }
    }
}

function get_device_type($user_agent)
{
    $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
    $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
    if (preg_match_all($mobile_regex, $user_agent)) {
        return 'mobile';
    } else {
        if (preg_match_all($tablet_regex, $user_agent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }
}
