<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request)
    {

        $settings = getCompanyAllSettings();

        if ($lang == '') {
            $lang =  isset($settings['default_language']) ? $settings['default_language'] : 'en';
        }

        \App::setLocale($lang);
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email',compact('lang'));
    }
}
