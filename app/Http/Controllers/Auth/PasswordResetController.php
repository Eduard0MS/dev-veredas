<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\RateLimiter; // Importação da Facade RateLimiter

class PasswordResetController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Mostrar o formulário de solicitação de redefinição de senha
     */
    public function showResetRequestForm()
    {
        return view('auth.passwords.reset_request');
    }

    /**
     * Processar a solicitação de redefinição de senha
     */
    public function sendResetLink(Request $request)
    {
        // Validar o identificador (e-mail)
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Armazenar o identificador na sessão para uso posterior
        $request->session()->put('password_reset_identifier', $request->input('email'));

        // Redirecionar para o formulário de verificação 2FA
        return redirect()->route('password.reset.2fa');
    }

    /**
     * Mostrar o formulário de verificação 2FA
     */
    public function show2faForm(Request $request)
    {
        if (!$request->session()->has('password_reset_identifier')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset_2fa');
    }

    /**
     * Processar a verificação 2FA
     */
    public function verify2fa(Request $request)
    {
        if (!$request->session()->has('password_reset_identifier')) {
            return redirect()->route('password.request');
        }

        // Validar o código 2FA
        $request->validate([
            '2fa_code' => 'required|digits:6',
        ]);

        $identifier = $request->session()->get('password_reset_identifier');

        // Recuperar o usuário pelo identificador (e-mail)
        $user = User::where('email', $identifier)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        if (!$user->two_factor_secret) {
            return back()->withErrors(['2fa_code' => 'Autenticação de dois fatores não está habilitada para esta conta.']);
        }

        // Definir uma chave única para rate limiting baseado no usuário
        $key = 'verify2fa|' . $user->id;

        // Verificar se o usuário excedeu o limite de tentativas
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['2fa_code' => 'Muitas tentativas. Por favor, tente novamente mais tarde.']);
        }

        // Incrementar o contador de tentativas
        RateLimiter::hit($key, 60); // Bloquear por 60 segundos

        // Verificar o código 2FA
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->input('2fa_code'));

        if ($valid) {
            // Limpar o contador de tentativas após sucesso
            RateLimiter::clear($key);

            // Código válido, permitir redefinição de senha
            $request->session()->put('password_reset_verified', true);
            return redirect()->route('password.reset.new');
        } else {
            return back()->withErrors(['2fa_code' => 'Código 2FA inválido.']);
        }
    }

    /**
     * Mostrar o formulário para definir uma nova senha
     */
    public function showNewPasswordForm(Request $request)
    {
        if (!$request->session()->has('password_reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset_new_password');
    }

    /**
     * Processar a nova senha
     */
    public function resetPassword(Request $request)
    {
        if (!$request->session()->has('password_reset_verified')) {
            return redirect()->route('password.request');
        }

        // Validar a nova senha
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $identifier = $request->session()->get('password_reset_identifier');

        // Recuperar o usuário pelo identificador (e-mail)
        $user = User::where('email', $identifier)->first();

        if (!$user) {
            return redirect()->route('password.request');
        }

        // Atualizar a senha do usuário
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Limpar dados da sessão
        $request->session()->forget('password_reset_identifier');
        $request->session()->forget('password_reset_verified');

        // Redirecionar para a página de login com mensagem de sucesso
        return redirect()->route('login')->with('status', 'Senha atualizada com sucesso. Por favor, faça login.');
    }
}
