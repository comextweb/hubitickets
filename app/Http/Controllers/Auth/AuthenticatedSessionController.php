<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Languages;
use App\Models\Utility;
use App\Models\User;
use App\Models\LoginDetails;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function __construct()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }
    }


    public function store(LoginRequest $request)
    {
        $settings = getCompanyAllSettings();
        $validation = [];
        if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
            if ($settings['google_recaptcha_version'] == 'v2-checkbox') {
                $validation['g-recaptcha-response'] = 'required';
            } elseif ($settings['google_recaptcha_version'] == 'v3') {


                $result = event(new VerifyReCaptchaToken($request));
                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]);

                    $validation['g-recaptcha-response'] = 'required';
                }
            } else {
                $validation = [];
            }
        } else {
            $validation = [];
        }

        $this->validate($request, $validation);
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
        if ($user->delete_status == 1) {
            auth()->logout();
        }

        if ($user->is_enable_login != 1 && $user->type != '0') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is disabled from admin.');
        }

        if(!moduleIsActive('CustomerLogin'))
        {
            if(($request->email == $user->email) && ($user->type == 'customer')) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Customer Login Module is disabled from admin.');
                
            }
        }

        if($user->type != 'customer')
        {
            return redirect()->intended(RouteServiceProvider::HOME);
        }else {

                return redirect()->intended(\Workdo\CustomerLogin\Providers\RouteServiceProvider :: HOME);                       
        }            
    }

    public function showLoginForm($lang = '')
    {
        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code',$lang)->first();
        $settings = getCompanyAllSettings();
        App::setLocale($lang);

        return view('auth.login', compact('lang', 'settings','language'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect()->route('login');
    }
}


