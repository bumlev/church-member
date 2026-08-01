<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.admin_seed_email', 'bumwejaychris@gmail.com');

        if (User::where('email', $email)->exists()) {
            return;
        }

        // ADMIN_SEED_PASSWORD is only ever set in local/staging env files, never
        // committed. Falls back to a random password when unset (e.g. production).
        $plainPassword = env('ADMIN_SEED_PASSWORD') ?: Str::random(32);

        User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make($plainPassword), // never a guessable literal
            'role' => 'admin',
            'must_change_password' => true,
            'email_verified_at' => now(),
        ]);

        if (! env('ADMIN_SEED_PASSWORD')) {
            $message = "Seeded admin user [{$email}] with generated password: {$plainPassword}. This is only shown once — it must be changed on first login.";

            $this->command?->getOutput()->writeln("<comment>{$message}</comment>");
            Log::warning($message);
        }
    }
}
