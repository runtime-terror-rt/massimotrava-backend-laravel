<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Execute seeds sequentially 
        $this->call([
            SystemPermissionSeeder::class, 
            RolesTableSeeder::class, 
            UserSeeder::class,
            PrivacyPolicySeeder::class,
            // SubscriptionPlanSeeder::class
        ]);
    }
}