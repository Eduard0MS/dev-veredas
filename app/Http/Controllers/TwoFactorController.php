<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwoFactorController extends Controller
{
    // Método para habilitar 2FA
    public function enable2Fa(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $google2fa = new Google2FA();
            $secretKey = $google2fa->generateSecretKey();
            $qrCode = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secretKey
            );

            return response()->json([
                'status' => true,
                'message' => 'OK',
                'data' => [
                    'qr' => $qrCode,
                    'secretKey' => $secretKey
                ]
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid Request']);
    }

    // Método para verificar e salvar 2FA
    public function verify2Fa(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'secretKey' => 'required|string'
            ]);

            $authId = Auth::id();
            $user = User::find($authId);
            $user->google2fa_secret = $request->secretKey;
            $user->save();

            return response()->json(['status' => true, 'message' => '2 Factor Authentication added successfully']);
        }

        return response()->json(['status' => false, 'message' => 'Invalid Request']);
    }

    // Método para desabilitar 2FA
    public function disable2Fa(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $user->google2fa_secret = null;
            $user->save();

            return response()->json(['status' => true, 'message' => '2FA desativado com sucesso.']);
        }

        return response()->json(['status' => false, 'message' => 'Invalid Request']);
    }
}
