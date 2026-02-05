<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. İcazələri (Permissions) Yaradın - Admin Panel menyusuna uyğun
        $permissions = [
            // Dashboard
            'dashboard_view',

            // Məzmun İdarəetməsi
            'page_view', 'page_create', 'page_edit', 'page_delete',
            'menu_view', 'menu_create',
            'post_view', 'post_create', 'post_edit', 'post_delete',
            'category_view', 'category_create',
            'comment_view', 'comment_delete',
            'media_view', 'media_upload',

            // Tibb Bölməsi
            'doctor_view', 'doctor_create', 'doctor_edit', 'doctor_delete',
            'clinic_view', 'clinic_create', 'clinic_edit', 'clinic_delete',
            'service_view', 'service_create',
            'reservation_view', 'reservation_manage',
            'doctor_request_view', 'doctor_request_manage',

            // E-Ticarət (Aptek)
            'product_view', 'product_create', 'product_edit', 'product_delete',
            'order_view', 'order_manage',
            'coupon_view', 'coupon_create',

            // İstifadəçilər
            'user_view', 'user_create', 'user_edit', 'user_delete',
            'role_view', 'role_create', 'role_edit', 'role_delete', // Rolları idarə etmək icazəsi

            // Tənzimləmələr
            'setting_view', 'setting_edit',
            'language_manage',
            'log_view',
            'backup_manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Rolları Yaradın
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']); // Həkim
        $userRole = Role::firstOrCreate(['name' => 'User']);     // Adi İstifadəçi

        // 3. İcazələri Rollara Verin
        // Super Adminə hər şeyi verməyə ehtiyac yoxdur, Gate::before ilə həll edəcəyik (Provider-də)
        // Amma Administratora ilkin olaraq bəzi icazələr verək
        $adminRole->syncPermissions([
            'dashboard_view',
            'post_view', 'post_create',
            'doctor_view',
            'product_view',
            'order_view'
        ]);

        // Həkim və User-ə admin panel icazəsi vermirik (boş qalır)

        // 4. Test İstifadəçiləri Yaradın

        // Super Admin
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@azdoktor.com',
        ], [
            'name' => 'Baş Admin',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Administrator (Məhdud səlahiyyətli)
        $manager = User::firstOrCreate([
            'email' => 'manager@azdoktor.com',
        ], [
            'name' => 'Menecer',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($adminRole);

        // Həkim
        $doctor = User::firstOrCreate([
            'email' => 'doctor@azdoktor.com',
        ], [
            'name' => 'Dr. Əliyev',
            'password' => Hash::make('password'),
        ]);
        $doctor->assignRole($doctorRole);
    }
}
