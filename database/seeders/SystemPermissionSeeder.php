<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission; 

class SystemPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comprehensive Massimo application operational permission blueprints based on routes
        $systemCapabilities = [
            // Core Dashboard & Analytics Matrix
            'manage-dashboard',
            'view-analytics',

            // Profile Registry Management
            'manage-profile',

            // User & Laboratorian Subsystems
            'manage-users',
            'manage-laboratorians',

            // Medical/Biomarker Reports Pipeline
            'view-reports',
            'create-reports',
            'delete-reports',
            'send-report-email',
            'download-report-pdf',

            // Kiosk & Medical Kits Management
            'manage-kits',

            // Categories & Sub Categories Node System
            'manage-categories',
            'manage-subcategories',

            // Laboratories Base Index
            'manage-laboratories',

            // Legal & System Configuration Utilities
            'manage-privacy-policy',
            'manage-faq',
            'manage-roles-permissions'
        ];

        // Process array and cleanly insert into database
        foreach ($systemCapabilities as $capability) {
            Permission::firstOrCreate([
                'name'       => $capability,
                'guard_name' => 'web'
            ]);
        }
    }
}