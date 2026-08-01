<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @return array{user: User, token: string}
     */
    public static function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user'  => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    public static function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    /**
     * Always attempts to send the reset link; the caller returns a generic
     * response regardless of the outcome to avoid leaking which emails exist.
     */
    public static function sendResetLink(string $email): void
    {
        Password::sendResetLink(['email' => $email]);
    }

    public static function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->must_change_password = false;
                $user->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );
    }
}
