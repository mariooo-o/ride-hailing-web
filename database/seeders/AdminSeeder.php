<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'         => 'Admin',
                'phone_number' => '08123456789',
                'password'     => 'admin123',
                'role'         => 'admin',
            ]
        );
    }
}