<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /** PUT /api/auth/password — logged-in self-service change. */
    public function update(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->forceFill(['password' => Hash::make($data['password'])])->save();

        return response()->json(['message' => 'Password updated.']);
    }

    /** POST /api/auth/forgot-password — sends a reset link if the email exists (never reveals whether it does). */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $throttleKey = 'forgot-password:'.strtolower($request->string('email'));
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return response()->json(['message' => 'If that email exists, a reset link has been sent.']);
        }
        RateLimiter::hit($throttleKey, 300);

        Password::sendResetLink($request->only('email'));

        // Deliberately generic response regardless of outcome — avoids
        // confirming or denying whether an email address has an account.
        return response()->json(['message' => 'If that email exists, a reset link has been sent.']);
    }

    /** POST /api/auth/reset-password — consumes the token from ResetPasswordNotification's link. */
    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return response()->json(['message' => 'Password has been reset. You can now log in.']);
    }
}
