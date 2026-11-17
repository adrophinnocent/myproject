<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@tourism.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'twinasafaris@gmail.com'],
            [
                'name' => 'Twina Safaris Admin',
                'password' => Hash::make('TwinaSafaris2025!'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );
    }
}
