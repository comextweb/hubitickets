<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\URL;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error(['message' => 'Invalid login details']);
        }

        $user              = User::where('email', $request['email'])->firstOrFail();
        $user->device_type = $request->device_type;
        $user->token       = $request->token;
        $user->save();

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'role'         => $user->type,
            'image_url'    => URL('storage/public').'/'.$user->avatar,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];

        return $this->success($data);
    }

    public function loginExternal(Request $request)
    {
        try {
            // 1. Obtener token del cuerpo JSON
        $token = $request->json('token');
        
        if (!$token) {
            return response()->json(['error' => 'Token requerido'], 400)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // 2. Validar token JWT
        $decoded = JWT::decode($token, new Key(env('JWT_HUBITICKETS_SHARED_SECRET'), 'HS256'));
        
        // 3. Buscar usuario
        $user = User::where('email', $decoded->email)->firstOrFail();

        // 4. Validaciones adicionales
        if ($user->delete_status == 1) {
            return response()->json(['error' => 'Cuenta eliminada'], 403);
        }

        if ($user->is_enable_login != 1 && $user->type != '0') {
            return response()->json(['error' => 'Cuenta deshabilitada'], 403);
        }

        // 5. Iniciar sesión
        Auth::login($user);
        $request->session()->regenerate();

        // 6. Determinar URL de redirección
        $redirectUrl = ($user->type != 'customer') 
            ? RouteServiceProvider::HOME
            : \Workdo\CustomerLogin\Providers\RouteServiceProvider::HOME;

        // 7. Retornar URL para redirección
        return response()->json(['redirect_url' => url($redirectUrl)])
        ->header('Access-Control-Allow-Origin', $request->header('Origin'))
        ->header('Access-Control-Allow-Credentials', 'true');
        

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error de autenticación: ' . $e->getMessage()
            ], 401);
        }

    }
    
    
    public function logout(Request $request)
    {
        $user = User::find($request->id);
        if(!empty($user)){
            $user->tokens()->delete();

            return $this->success(['message' => 'logout successfully']);
        }else{
            return $this->error(['message' => 'Invalid login details']);
        }
    }




}
