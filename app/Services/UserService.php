<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\NewAccountNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    public static function getAllUsers(): Collection
    {
        return User::orderBy('name')->get();
    }


    /**
     * `role` and `must_change_password` are deliberately excluded from
     * `User`'s Fillable attribute, so they can't be set through this array —
     * they're assigned directly on the model instance below instead.
     *
     * @return array{user: User, temporary_password: string}
     */
    public static function createUser(array $data): array
    {
        $temporaryPassword = Str::random(32);

        $user = new User([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($temporaryPassword),
        ]);
        $user->role = 'user';
        $user->must_change_password = true;
        $user->email_verified_at = now();
        $user->save();

        $user->notify(new NewAccountNotification($temporaryPassword));

        Log::info("Admin-created user [{$user->email}] issued a temporary password via email.");

        return ['user' => $user, 'temporary_password' => $temporaryPassword];
    }

    public static function updateUser(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    /**
     * Set outside of mass assignment on purpose — `role` is guarded on the
     * model, so this is the only path allowed to change it.
     */
    public static function updateRole(User $user, string $role): User
    {
        $user->role = $role;
        $user->save();

        return $user->fresh();
    }

    public static function deleteUser(User $user): void
    {
        $user->delete();
    }
}
