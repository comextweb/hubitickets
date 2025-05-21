<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings;
use App\Mail\EmailTest;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use ApiResponser;

    public function site_setting_page(Request $request)
    {
        $setting      = getCompanyAllSettings($request->id);
        if(!empty($setting))
        {
            $data = [
                'default_language'   => isset($setting['default_language']) ? $setting['default_language'] : '',
                'site_rtl'           => isset($setting['site_rtl']) ? $setting['site_rtl'] : '',
                'gdpr_cookie'        => isset($setting['gdpr_cookie']) ? $setting['gdpr_cookie'] : '',
                'App_Name'           => isset($setting['app_name']) ? $setting['app_name'] : '',
                'footer_text'        => isset($setting['footer_text']) ? $setting['footer_text'] : '',
                'cookie_text'        => isset($setting['cookie_text']) ? $setting['cookie_text'] : '',
                'company_favicon'    => isset($setting['favicon']) && checkFile($setting['favicon']) ? getFile($setting['favicon']) : '',
                'company_logo'       => isset($setting['logo']) && checkFile($setting['logo']) ? getFile($setting['logo']) : '',
                'company_logo_light' => isset($setting['white_logo']) && checkFile($setting['white_logo']) ? getFile($setting['white_logo']) : '',
            ];

            $data = [
                'site' => $data
            ];

            return $this->success($data);   
        } 
        else{
            return $this->error([] , 'Site setting not found' , 200);
        }
    }

    public function sitesetting(Request $request)
    {
        $post = [];
        $rules = [
            'app_name'         => 'required|string|max:50',
            'default_language' => 'required|string|max:50',
            'site_rtl'         => 'required|string|max:50',
            'gdpr_cookie'      => 'required|string|max:50',
            'cookie_text'      => 'required|string|max:50',
            'footer_text'      => 'required|string|max:50',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        if($request->favicon)
        {
            $favicon = 'favicon_' . time() . '.png';

            $path = uploadFile($request, 'favicon', $favicon, 'logo', []);
            if ($path['flag'] == 1) {
                $favicon = $path['url'];
                $post['favicon'] = $favicon;
            } 
        }
        if(!empty($request->logo))
        {   
            $logoName = 'logo-dark_' . time() . '.png';
            $path = uploadFile($request, 'logo', $logoName, 'logo', []);
            if ($path['flag'] == 1) {
                $logo = $path['url'];
                $post['logo'] = $logo;
            }             
        }
        if($request->white_logo)
        {
            $logoName = 'logo-light_' . time() . '.png';
            $path = uploadFile($request, 'white_logo', $logoName, 'logo', []);
            if ($path['flag'] == 1) {
                $logo = $path['url'];
                $post['white_logo'] = $logo;
            } 
        }
    
        $post['app_name']         = $request->app_name;
        $post['default_language'] = $request-> default_language;
        $post['site_rtl']         = $request-> site_rtl;
        $post['footer_text']      = $request-> footer_text;
        $post['gdpr_cookie']      = $request-> gdpr_cookie;
        $post['cookie_text']      = $request->has('cookie_text') ? $request-> cookie_text : '';
                
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            foreach ($post as $key => $value) {
                $data = [
                    'name'       => $key,
                    'created_by' => creatorId(),
                ];
                Settings::updateOrInsert($data, ['value' => $value]);
            }
        }
        companySettingCacheForget(creatorId());
        
        $data = [
            'site' => $post
        ];
        return $this->success($data);
    }

    public function emailsettingpage(Request $request){

        if($request->id == null)
        {
            $settings = getCompanyAllSettings();
        }
        else
        {
            $settings = getCompanyAllSettings($request->id);
        }

        $arrEnv = [
            'mail_driver'       => !empty($settings['mail_driver']) ? $settings['mail_driver'] : '',
            'mail_host'         => !empty($settings['mail_host']) ? $settings['mail_host'] : '',
            'mail_port'         => !empty($settings['mail_port']) ? $settings['mail_port'] :'',
            'mail_username'     => !empty($settings['mail_username']) ? $settings['mail_username'] : '',
            'mail_password'     => !empty($settings['mail_password']) ? $settings['mail_password'] : '',
            'mail_encryption'   => !empty($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
            'mail_from_address' => !empty($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
            'mail_from_name'    => !empty($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
        ];

        $data = [
            'site' => $arrEnv
        ];

        return $this->success($data);
    }

    public function emailsetting(Request $request)
    {      
        $rules = [
            'mail_driver'       => 'required|string|max:50',
            'mail_host'         => 'required|string|max:50',
            'mail_port'         => 'required|string|max:50',
            'mail_username'     => 'required|string|max:50',
            'mail_password'     => 'required|string|max:255',
            'mail_encryption'   => 'required|string|max:50',
            'mail_from_address' => 'required|string|max:50',
            'mail_from_name'    => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $post = $request->all();
        unset($post['_token']);

        foreach ($post as $key => $value) {
            $data = [
                'name'       => $key,
                'created_by' => creatorId(),
            ];

            Settings::updateOrInsert($data, ['value' => $value]);
        }

        companySettingCacheForget(creatorId());
        
        $data = [
            'site' => $post
        ];

        return $this->success($data);
    }

    public function testEmailSend(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                               'user_mail'         => 'required|email',
                               'mail_driver'       => 'required',
                               'mail_host'         => 'required',
                               'mail_port'         => 'required',
                               'mail_username'     => 'required',
                               'mail_password'     => 'required',
                               'mail_from_address' => 'required',
                               'mail_from_name'    => 'required',
                           ]
        );
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        try
        {
            config(
                [
                    'mail.driver'       => $request->mail_driver,
                    'mail.host'         => $request->mail_host,
                    'mail.port'         => $request->mail_port,
                    'mail.encryption'   => $request->mail_encryption,
                    'mail.username'     => $request->mail_username,
                    'mail.password'     => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name'    => $request->mail_from_name,
                ]
            );
            Mail::to($request->user_mail)->send(new EmailTest());
        }
        catch(\Exception $e)
        {
            return $this->error([] , $e->getMessage() , 200);
        }

        $data = [
            'is_success' => true,
            'message'    => __('Email send Successfully'),
        ];
        return $this->success($data);
    }

    public function recaptchasetting(Request $request)
    {
        $rules = [];

        if($request->recaptcha_module == 'yes')
        {
            $rules['google_recaptcha_key']     = 'required|string|max:50';
            $rules['google_recaptcha_secret']  = 'required|string|max:50';
            $rules['google_recaptcha_version'] = 'required';
        }

        $validator = Validator::make(
            $request->all(), $rules
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        if (!isset($post['RECAPTCHA_MODULE'])) {
            $post['RECAPTCHA_MODULE']  = $request->recaptcha_module  ?? 'no';
        }

        if (!isset($post['NOCAPTCHA_SITEKEY'])) {
            $post['NOCAPTCHA_SITEKEY'] = $request->google_recaptcha_key ?? '';
        }

        if (!isset($post['NOCAPTCHA_SECRET'])) {
            $post['NOCAPTCHA_SECRET']  = $request->google_recaptcha_secret ?? '';
        }

        if (!isset($post['google_recaptcha_version'])) {
            $post['google_recaptcha_version'] = $request->google_recaptcha_version ?? '';
        }

        foreach ($post as $key => $value) {
            $data = [
                'name'       => $key,
                'created_by' => creatorId(),
            ];

            Settings::updateOrInsert($data, ['value' => $value]);
        }

        companySettingCacheForget(creatorId());

        $data = [
            'site' => $post
        ];

        return $this->success($data);
    }

    public function langList()
    {
        $lang = languages();

        $data = [
            'language' => array_keys($lang)
        ];

        return $this->success($data);
    }    
}

