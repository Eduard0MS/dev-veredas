<?php

// app/Http/Controllers/Auth/CustomResetPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Services\TwoFactorService;

class CustomResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * The TwoFactorService instance.
     *
     * @var \App\Services\TwoFactorService
     */
    protected $twoFactorService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\TwoFactorService  $twoFactorService
     * @return void
     */
    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Display the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Handle a reset password request with 2FA verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'token'            => 'required',
            'email'            => 'required|email',
            'password'         => 'required|confirmed|min:8',
            'two_factor_code'  => 'required|string',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }

        // Check if the user has 2FA enabled
        if ($user->google2fa_secret) {
            // Verify the 2FA code using TwoFactorService
            $isValid = $this->twoFactorService->verifyCode($user, $request->two_factor_code);

            if (!$isValid) {
                throw ValidationException::withMessages([
                    'two_factor_code' => ['The two-factor authentication code is invalid.'],
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                'two_factor_code' => ['Two-factor authentication is not enabled for this user.'],
            ]);
        }

        // Proceed to reset the password using the broker
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // Check the response and redirect accordingly
        return $response == Password::PASSWORD_RESET
            ? redirect($this->redirectPath())->with('status', __($response))
            : back()->withErrors(['email' => [__($response)]]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \App\Models\User  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        // Hash and set the new password
        $user->password = Hash::make($password);

        // Invalidate existing tokens
        $user->setRememberToken(Str::random(60));

        // Save the user
        $user->save();

        // Log the user in
        $this->guard()->login($user);
    }
}
