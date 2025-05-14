<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Languages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Utility;
use Illuminate\Support\Facades\App;
use Exception;

class PasswordResetLinkController extends Controller
{

    public function create($lang = '')
    {
        $settings = getCompanyAllSettings();
        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code', $lang)->first();
        App::setLocale($lang);
        return view('auth.passwords.email', compact('settings', 'lang', 'language'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $settings = getCompanyAllSettings();

        if ((isset($settings['mail_driver']) && $settings['mail_driver']) && (isset($settings['mail_host']) && $settings['mail_host']) && (isset($settings['mail_port']) && $settings['mail_port']) && (isset($settings['mail_encryption']) && $settings['mail_encryption']) && (isset($settings['mail_username']) && $settings['mail_username']) && (isset($settings['mail_password']) && $settings['mail_password']) && (isset($settings['mail_from_address']) && $settings['mail_from_address']) && (isset($settings['mail_from_name']) && $settings['mail_from_name'])) {
            try {

                setSMTPConfig();
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
            } catch (Exception $e) {
                return redirect()->back()->with('Error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('Error', 'Email SMTP settings does not configured so please contact to your site admin.');
        }
    }
}
