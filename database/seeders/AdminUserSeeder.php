<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = env('ADMIN_SEED_PASSWORD', 'TwinaSafaris2026!');

        $adminEmails = [
            'twinasafaris@gmail.com' => 'Twina Safaris Admin',
            'admin@tourism.com' => 'Admin User',
            'bellainnos@gmail.com' => 'Innocent Bellain',
            'admin@twinasafaris.com' => 'System Administrator',
            'info@twinasafaris.com' => 'Twina Support Admin',
        ];

        foreach ($adminEmails as $email => $name) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->is_admin = true;
                $user->save();
            } else {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($defaultPassword),
                    'email_verified_at' => now(),
                    'is_admin' => true,
                ]);
            }
        }
    }
}
