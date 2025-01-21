<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Métodos padrão do Jetstream/Fortify
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Personaliza a autenticação para incluir 'orgao' e verificar se a conta está ativa
        Fortify::authenticateUsing(function (Request $request) {
            // Valida os campos de entrada
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
                'orgao'    => 'required|string',
            ]);

            // Tenta encontrar o usuário com base no email e órgão
            $user = User::where('email', $request->email)
                ->where('orgao', $request->orgao)
                ->first();

            // 1) Verifica se o usuário com esse email/órgão existe
            if (! $user) {
                throw ValidationException::withMessages([
                    'orgao' => __('Nenhum usuário encontrado com este e-mail e órgão.'),
                ]);
            }

            // 2) Verifica se a senha está correta
            if (! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => __('A senha fornecida está incorreta.'),
                ]);
            }

            // 3) Verifica se a conta está ativa
            if (! $user->is_active) {
                throw ValidationException::withMessages([
                    'email' => __('Sua conta está inativa. Entre em contato com o suporte.'),
                ]);
            }

            // Se passou por todas as verificações, retorna o usuário autenticado
            return $user;
        });

        // Limite de tentativas de login
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Limite de tentativas de verificação em 2 fatores
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}
