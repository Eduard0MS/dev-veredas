<?php
// app/Services/TwoFactorService.php

namespace App\Services;

use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\User;

class TwoFactorService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Verifica se o código 2FA é válido para o usuário.
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->google2fa_secret) {
            return false;
        }

        return $this->google2fa->verifyKey($user->google2fa_secret, $code);
    }

    /**
     * Gera um novo segredo 2FA para o usuário.
     *
     * @param User $user
     * @return string
     */
    public function generateSecret(User $user): string
    {
        $secret = $this->google2fa->generateSecretKey();
        $user->google2fa_secret = $secret;
        $user->save();

        return $secret;
    }

    /**
     * Gera o QR Code para o usuário.
     *
     * @param User $user
     * @return string
     */
    public function getQRCodeInline(User $user): string
    {
        return $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
    }
}

