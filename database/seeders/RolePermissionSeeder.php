<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users', 'module' => 'users', 'description' => 'Can view users list'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'module' => 'users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'module' => 'users', 'description' => 'Can edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'module' => 'users', 'description' => 'Can delete users'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'module' => 'users', 'description' => 'Full user management access'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'view-roles', 'module' => 'roles', 'description' => 'Can view roles list'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'module' => 'roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'module' => 'roles', 'description' => 'Can edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'module' => 'roles', 'description' => 'Can delete roles'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'module' => 'roles', 'description' => 'Full role management access'],

            // Sabeel Management
            ['name' => 'View Sabeels', 'slug' => 'view-sabeels', 'module' => 'sabeels', 'description' => 'Can view sabeels list'],
            ['name' => 'Create Sabeels', 'slug' => 'create-sabeels', 'module' => 'sabeels', 'description' => 'Can create new sabeels'],
            ['name' => 'Edit Sabeels', 'slug' => 'edit-sabeels', 'module' => 'sabeels', 'description' => 'Can edit existing sabeels'],
            ['name' => 'Delete Sabeels', 'slug' => 'delete-sabeels', 'module' => 'sabeels', 'description' => 'Can delete sabeels'],
            ['name' => 'Manage Sabeels', 'slug' => 'manage-sabeels', 'module' => 'sabeels', 'description' => 'Full sabeel management access'],

            // Mumin Management
            ['name' => 'View Mumineen', 'slug' => 'view-mumineen', 'module' => 'mumineen', 'description' => 'Can view mumineen list'],
            ['name' => 'Create Mumineen', 'slug' => 'create-mumineen', 'module' => 'mumineen', 'description' => 'Can create new mumineen'],
            ['name' => 'Edit Mumineen', 'slug' => 'edit-mumineen', 'module' => 'mumineen', 'description' => 'Can edit existing mumineen'],
            ['name' => 'Delete Mumineen', 'slug' => 'delete-mumineen', 'module' => 'mumineen', 'description' => 'Can delete mumineen'],
            ['name' => 'Manage Mumineen', 'slug' => 'manage-mumineen', 'module' => 'mumineen', 'description' => 'Full mumin management access'],

            // Location Management
            ['name' => 'View Locations', 'slug' => 'view-locations', 'module' => 'locations', 'description' => 'Can view locations list'],
            ['name' => 'Create Locations', 'slug' => 'create-locations', 'module' => 'locations', 'description' => 'Can create new locations'],
            ['name' => 'Edit Locations', 'slug' => 'edit-locations', 'module' => 'locations', 'description' => 'Can edit existing locations'],
            ['name' => 'Delete Locations', 'slug' => 'delete-locations', 'module' => 'locations', 'description' => 'Can delete locations'],
            ['name' => 'Manage Locations', 'slug' => 'manage-locations', 'module' => 'locations', 'description' => 'Full location management access'],

            // Event Management
            ['name' => 'View Events', 'slug' => 'view-events', 'module' => 'events', 'description' => 'Can view events list'],
            ['name' => 'Create Events', 'slug' => 'create-events', 'module' => 'events', 'description' => 'Can create new events'],
            ['name' => 'Edit Events', 'slug' => 'edit-events', 'module' => 'events', 'description' => 'Can edit existing events'],
            ['name' => 'Delete Events', 'slug' => 'delete-events', 'module' => 'events', 'description' => 'Can delete events'],
            ['name' => 'Manage Events', 'slug' => 'manage-events', 'module' => 'events', 'description' => 'Full event management access'],

            // Takhmeen Management
            ['name' => 'View Takhmeen', 'slug' => 'view-takhmeen', 'module' => 'takhmeen', 'description' => 'Can view takhmeen list'],
            ['name' => 'Create Takhmeen', 'slug' => 'create-takhmeen', 'module' => 'takhmeen', 'description' => 'Can create new takhmeen'],
            ['name' => 'Edit Takhmeen', 'slug' => 'edit-takhmeen', 'module' => 'takhmeen', 'description' => 'Can edit existing takhmeen'],
            ['name' => 'Delete Takhmeen', 'slug' => 'delete-takhmeen', 'module' => 'takhmeen', 'description' => 'Can delete takhmeen'],
            ['name' => 'Manage Takhmeen', 'slug' => 'manage-takhmeen', 'module' => 'takhmeen', 'description' => 'Full takhmeen management access'],

        // NOC Management
        ['name' => 'View NOC', 'slug' => 'view-noc', 'module' => 'noc', 'description' => 'Can view NOC list'],
        ['name' => 'Create NOC', 'slug' => 'create-noc', 'module' => 'noc', 'description' => 'Can create new NOC'],
        ['name' => 'Edit NOC', 'slug' => 'edit-noc', 'module' => 'noc', 'description' => 'Can edit existing NOC'],
        ['name' => 'Delete NOC', 'slug' => 'delete-noc', 'module' => 'noc', 'description' => 'Can delete NOC'],
        ['name' => 'Manage NOC', 'slug' => 'manage-noc', 'module' => 'noc', 'description' => 'Full NOC management access'],
        
        // Settings Management (Admin Only)
        ['name' => 'View Settings', 'slug' => 'view-settings', 'module' => 'settings', 'description' => 'Can view settings dashboard'],
        ['name' => 'Manage Migrations', 'slug' => 'manage-migrations', 'module' => 'settings', 'description' => 'Can run and manage database migrations'],
        ['name' => 'Manage Seeders', 'slug' => 'manage-seeders', 'module' => 'settings', 'description' => 'Can run and manage database seeders'],
        ['name' => 'Manage Storage', 'slug' => 'manage-storage', 'module' => 'settings', 'description' => 'Can manage file storage and cache'],
        ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'module' => 'settings', 'description' => 'Full settings management access'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Can view and manage sabeels, mumineen, locations, and events',
                'is_active' => true,
            ]
        );

        $staffRole = Role::firstOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff',
                'description' => 'Can view sabeels, mumineen, locations, and events',
                'is_active' => true,
            ]
        );

        $customerRole = Role::firstOrCreate(
            ['slug' => 'customer'],
            [
                'name' => 'Customer',
                'description' => 'Basic user access',
                'is_active' => true,
            ]
        );

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::all()->pluck('id')->toArray());

        $managerRole->syncPermissions(Permission::whereIn('slug', [
            'view-users', 'view-roles',
            'view-sabeels', 'create-sabeels', 'edit-sabeels', 'delete-sabeels',
            'view-mumineen', 'create-mumineen', 'edit-mumineen', 'delete-mumineen',
            'view-locations', 'create-locations', 'edit-locations', 'delete-locations',
            'view-events', 'create-events', 'edit-events', 'delete-events',
            'view-takhmeen', 'create-takhmeen', 'edit-takhmeen', 'delete-takhmeen',
            'view-noc', 'create-noc', 'edit-noc', 'delete-noc'
        ])->pluck('id')->toArray());

        $staffRole->syncPermissions(Permission::whereIn('slug', [
            'view-users',
            'view-sabeels', 'view-mumineen', 'view-locations', 'view-events', 'view-takhmeen', 'view-noc'
        ])->pluck('id')->toArray());

        $customerRole->syncPermissions([]);

        // Assign admin role to admin user
        $adminUser = User::where('email', 'admin@seatflow.com')->first();
        if ($adminUser && !$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}