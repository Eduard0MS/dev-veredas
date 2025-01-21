<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

// Rota Raiz
Route::get('/', function () {
    return view('welcome');
});

// Rotas protegidas por autenticação e verificação
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas Admin
    Route::get('/home', [AdminController::class, 'index'])->name('home');
    Route::post('/home', [AdminController::class, 'loginAction']);

    // Rotas de 2FA
    Route::post('/enable-2fa', [TwoFactorController::class, 'enable2Fa'])->name('enable-2fa');
    Route::post('/verify-2fa', [TwoFactorController::class, 'verify2Fa'])->name('verify-2fa');
    Route::post('/disable-2fa', [TwoFactorController::class, 'disable2Fa'])->name('disable-2fa');
});

// Rotas de 2FA durante o processo de login
Route::middleware(['auth:sanctum'])->group(function () {
    // Página de Verificação de 2FA
    Route::get('/login-2fa', [AuthController::class, 'login2fa'])->name('login2fa');

    // Verificação de 2FA
    Route::post('/login-2fa', [AuthController::class, 'verify'])->name('2fa.verify');
});

// Rotas de Logout (Protegidas por autenticação)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['guest'])->group(function () {
    // Formulário de solicitação de redefinição de senha
    Route::get('password/reset', [PasswordResetController::class, 'showResetRequestForm'])->name('password.request');

    // Processar a solicitação de redefinição de senha
    Route::post('password/reset', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

    // Formulário de verificação 2FA
    Route::get('password/reset/2fa', [PasswordResetController::class, 'show2faForm'])->name('password.reset.2fa');

    // Processar a verificação 2FA com Rate Limiting
    Route::post('password/reset/2fa', [PasswordResetController::class, 'verify2fa'])
        ->middleware('throttle:5,1') // 5 tentativas por minuto
        ->name('password.reset.verify2fa');

    // Formulário para definir nova senha
    Route::get('password/reset/new', [PasswordResetController::class, 'showNewPasswordForm'])->name('password.reset.new');

    // Processar a nova senha
    Route::post('password/reset/new', [PasswordResetController::class, 'resetPassword'])->name('password.reset.update');
});
