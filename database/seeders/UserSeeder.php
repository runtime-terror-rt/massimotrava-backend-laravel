<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $admin->assignRole('admin');
        
        // Create users
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $user->assignRole('user');

        $lab = User::updateOrCreate(
            ['email' => 'lab@example.com'],
            [
                'name' => 'Lab User',
                'password' => Hash::make('Password456!'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );

        $lab->assignRole('lab');
    }
}
