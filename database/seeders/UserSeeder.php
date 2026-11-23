<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo customers only. The real admin account is created by AdminUserSeeder.
        User::factory(5)->create();
    }
}
