<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Crypt;

class LoginExternalController extends Controller
{
    public function handle(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token no proporcionado.');

        }
    
        try {
            $decrypted = Crypt::decryptString($token);
            $decoded = JWT::decode($decrypted, new Key(env('JWT_HUBITICKETS_SHARED_SECRET'), 'HS256'));
        
            $email = $decoded->email ?? null;
            if (!$email) {
                return redirect()->route('login')->with('error', 'Token inválido: email no presente.');

            }
    
            $user = User::where('email', $email)->first();
    
            if (!$user) {
                return redirect()->route('login')->with('error', 'Usuario no encontrado.');

            }
    
            if ($user->delete_status == 1) {
                return redirect()->route('login')->with('error', 'Tu cuenta ha sido eliminada.');

            }
    
            if ($user->is_enable_login != 1 && $user->type != '0') {
                return redirect()->route('login')->with('error', 'Tu cuenta ha sido deshabilitada por el administrador.');
            }
    
            if (!moduleIsActive('CustomerLogin') && $user->type == 'customer') {
                return redirect()->route('login')->with('error', 'El módulo de login para clientes está deshabilitado.');
            }
    
            Auth::login($user);
            $request->session()->regenerate();
    
            // ✅ Redirección según tipo
            if ($user->type != 'customer') {
                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                return redirect()->intended(\Workdo\CustomerLogin\Providers\RouteServiceProvider::HOME);
            }
    
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Token inválido.' . $e->getMessage());

        }
    }
    
    
}
