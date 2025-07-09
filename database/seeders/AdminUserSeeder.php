<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],  // unique identifier
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('admin12345'), // change to a strong password!
                'role' => 'admin',
                'status' => true,
                'is_admin' => true,
                'is_parent' => false,
                'remember_token' => null,
            ]
        );
    }
}
