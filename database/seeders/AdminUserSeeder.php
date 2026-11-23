<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL', 'admin@twinasafaris.com');
        $password = env('ADMIN_SEED_PASSWORD');
        $generated = false;

        if (empty($password)) {
            $password = Str::random(16);
            $generated = true;
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Twina Safaris Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        if (! $admin->is_admin) {
            $admin->update(['is_admin' => true]);
        }

        if ($generated && $this->command) {
            $this->command->warn("Admin account created: {$email}");
            $this->command->warn("Generated password: {$password}");
            $this->command->warn('Store this password securely and change it after first login. Set ADMIN_SEED_PASSWORD in .env to choose your own.');
        }
    }
}
