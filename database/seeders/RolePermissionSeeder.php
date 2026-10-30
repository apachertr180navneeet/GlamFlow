<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Store;
use App\Models\Setting;
use Hash, DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Appointment Module
            'view appointments', 'create appointments', 'edit appointments', 'delete appointments',
            
            // Package Module
            'view packages', 'create packages', 'edit packages', 'delete packages',

            // Attendance Module
            'view attendances', 'create attendances', 'edit attendances', 'delete attendances',

            // User Module
            'view users', 'create users', 'edit users', 'delete users',

            // Role Module
            'view roles', 'create roles', 'edit roles', 'delete roles',

            // Permission Module
            'view permissions', 'create permissions', 'edit permissions', 'delete permissions',

            // Store Module
            'view stores', 'create stores', 'edit stores', 'delete stores',

            // Branch Module
            'view branches', 'create branches', 'edit branches', 'delete branches',

            // Customer Module
            'view customers', 'create customers', 'edit customers', 'delete customers', 'view birthday customers', 'view birthday msg logs', 'send birthday whatsapp',

            // Category Master Module
            'view service categories', 'create service categories', 'edit service categories', 'delete service categories',

            // Duration Master Module
            'view service durations', 'create service durations', 'edit service durations', 'delete service durations',

            // Service Module
            'view services', 'create services', 'edit services', 'delete services',

            // Staff Module
            'view staffs', 'create staffs', 'edit staffs', 'delete staffs',

            // Leave Request Module
            'view leave requests', 'create leave requests', 'approve leave requests', 'delete leave requests',

            // Holiday Module
            'view holidays', 'create holidays', 'edit holidays', 'delete holidays',

            // Payroll Module
            'view payrolls', 'create payrolls', 'edit payrolls', 'delete payrolls',

            // Employee Advances Module
            'view advances', 'create advances', 'edit advances', 'delete advances',

            // Item Master Module
            'view items', 'create items', 'edit items', 'delete items',

            // Laundry Order Module
            'view laundry orders', 'create laundry orders', 'edit laundry orders', 'delete laundry orders', 'receive laundry orders', 'pay laundry orders',

            // Expense Category Module
            'view expense categories', 'create expense categories', 'edit expense categories', 'delete expense categories',

            // Expense Module
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',

            // Feedback Module
            'view feedbacks', 'delete feedbacks',

            // Report Module
            'view reports', 'export reports', 'view sales reports', 'view customer reports', 'view staff reports', 'view expense reports', 'view store ledger', 'view package usage', 'view laundry report', 'view outstanding report',
            'export sales pdf', 'export sales excel', 'export store ledger pdf', 'export store ledger excel',

            // Setting Module
            'view settings', 'edit settings',

            // Activity Log Module
            'view activity logs',

            // CRM Module
            'view crm', 'send crm', 'view sms logs', 'view whatsapp logs', 'view email logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 1. Super Admin & Admin Roles -> Has ALL module permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // 2. Manager and Store Manager Roles -> Full access to operational modules
        $managerPermissions = [
            // Items
            'view items', 'create items', 'edit items', 'delete items',
            // Appointments
            'view appointments', 'create appointments', 'edit appointments', 'delete appointments',
            // Packages
            'view packages', 'create packages', 'edit packages', 'delete packages',
            // Attendances
            'view attendances', 'create attendances', 'edit attendances', 'delete attendances',
            // Customers
            'view customers', 'create customers', 'edit customers', 'delete customers', 'view birthday customers', 'view birthday msg logs', 'send birthday whatsapp',
            // Category Master
            'view service categories', 'create service categories', 'edit service categories', 'delete service categories',
            // Duration Master
            'view service durations', 'create service durations', 'edit service durations', 'delete service durations',
            // Services
            'view services', 'create services', 'edit services', 'delete services',
            // Staff
            'view staffs', 'create staffs', 'edit staffs', 'delete staffs',
            // Leave Requests
            'view leave requests', 'create leave requests', 'approve leave requests', 'delete leave requests',
            // Holidays
            'view holidays', 'create holidays', 'edit holidays', 'delete holidays',
            // Payrolls
            'view payrolls', 'create payrolls', 'edit payrolls', 'delete payrolls',
            // Advances
            'view advances', 'create advances', 'edit advances', 'delete advances',
            // Laundry Orders
            'view laundry orders', 'create laundry orders', 'edit laundry orders', 'delete laundry orders', 'receive laundry orders', 'pay laundry orders',
            // Expense Categories
            'view expense categories', 'create expense categories', 'edit expense categories', 'delete expense categories',
            // Expenses
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            // Reports
            'view reports', 'export reports', 'view sales reports', 'view customer reports', 'view staff reports', 'view expense reports', 'view store ledger', 'view package usage', 'view laundry report', 'view outstanding report',
            // Feedbacks
            'view feedbacks', 'delete feedbacks',
            // CRM
            'view crm', 'send crm', 'view sms logs', 'view whatsapp logs', 'view email logs',
            // Stores & Branches view
            'view stores', 'view branches',
        ];

        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions($managerPermissions);

        // Store Manager with Manager permissions for system compatibility
        $storeManagerRole = Role::firstOrCreate(['name' => 'Store Manager', 'guard_name' => 'web']);
        $storeManagerRole->syncPermissions($managerPermissions);

        // Assign Super Admin & Admin roles to all existing admin users
        $adminUsers = User::where(function($q) {
            $q->whereIn('role', ['admin', 'super_admin', 'superadmin', 'Super Admin', 'Admin'])
              ->orWhere('email', 'like', '%admin%');
        })->get();

        foreach ($adminUsers as $adminUser) {
            if (!$adminUser->hasRole('Super Admin')) {
                $adminUser->assignRole($superAdminRole);
            }
            if (!$adminUser->hasRole('Admin')) {
                $adminUser->assignRole($adminRole);
            }
        }

        $defaultSettings = [
            'company_name' => 'Infinity Salon',
            'company_phone' => '1234567890',
            'company_email' => 'info@infinitysalon.com',
            'company_address' => '123 Business Street',
            'sms_enabled' => 'false',
            'whatsapp_enabled' => 'false',
            'email_enabled' => 'false',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::set($key, $value);
        }

        // Clear cache again after syncing
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if ($this->command) {
            $this->command->info('Roles and permissions seeded and assigned to Super Admin & Admin successfully!');
        }
    }
}

