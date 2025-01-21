<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // Método para exibir a página de login
    public function index()
    {
        return view('auth.login');
    }

    // Método para processar o login
    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (auth()->attempt($request->only('email', 'password'), true)) {
            $google2fa = new Google2FA();

            $user = auth()->user();
            if ($user->google2fa_secret) {
                $request->session()->put('2fa:user:id', $user->id);
                $request->session()->put('2fa:user:credentials', $request->only('email', 'password'));
                $request->session()->put('2fa:auth:attempt', true);

                auth()->logout();
                return redirect()->route('login2fa');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid Credentials']);
    }

    // Método para exibir a página de verificação de 2FA
    public function login2fa(Request $request)
    {
        $user_id = $request->session()->get('2fa:user:id');
        if (!$user_id) {
            return redirect()->route('login');
        }
        return view('auth.login2fa');
    }

    // Método para verificar o código de 2FA durante o login
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user_id = $request->session()->get('2fa:user:id');
        $credentials = $request->session()->get('2fa:user:credentials');
        $attempt = $request->session()->get('2fa:auth:attempt', false);

        if (!$user_id || !$attempt) {
            return redirect()->route('login');
        }

        $user = User::find($user_id);

        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();
        $otp_secret = $user->google2fa_secret;

        if (!$google2fa->verifyKey($otp_secret, $request->one_time_password)) {
            throw ValidationException::withMessages([
                'one_time_password' => [__('The one time password is invalid.')],
            ]);
        }

        if (auth()->attempt($credentials, true)) {
            $request->session()->remove('2fa:user:id');
            $request->session()->remove('2fa:user:credentials');
            $request->session()->remove('2fa:auth:attempt');

            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->withErrors([
            'password' => __('The provided credentials are incorrect.'),
        ]);
    }

    // Método para realizar o logout
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
