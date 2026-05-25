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
        $systemCapabilities = [
            'manage-dashboard',
            'view-analytics',
            'manage-profile',
            'manage-labs',
            'manage-users',
            'manage-laboratorians',
            'view-reports',
            'create-reports',
            'delete-reports',
            'send-report-email',
            'download-report-pdf',
            'manage-kits',
            'manage-categories',
            'manage-subcategories',
            'view-payments',
            'manage-contents',
            'manage-campaigns',
            'manage-settings',
            'manage-courier',
            'manage-roles-permissions',
            'view-audit-logs'
        ];

        foreach ($systemCapabilities as $capability) {
            Permission::firstOrCreate([
                'name'       => $capability,
                'guard_name' => 'web'
            ]);
        }
    }
}