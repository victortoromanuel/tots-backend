<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tots.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@tots.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@tots.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
