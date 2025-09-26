<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\Role;

class AddSettingsPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:add-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add settings permissions to the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = [
            ['name' => 'View Settings', 'slug' => 'view-settings', 'module' => 'settings', 'description' => 'Can view settings dashboard'],
            ['name' => 'Manage Migrations', 'slug' => 'manage-migrations', 'module' => 'settings', 'description' => 'Can run and manage database migrations'],
            ['name' => 'Manage Seeders', 'slug' => 'manage-seeders', 'module' => 'settings', 'description' => 'Can run and manage database seeders'],
            ['name' => 'Manage Storage', 'slug' => 'manage-storage', 'module' => 'settings', 'description' => 'Can manage file storage and cache'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'module' => 'settings', 'description' => 'Full settings management access'],
        ];

        foreach ($permissions as $permission) {
            $created = Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
            
            if ($created->wasRecentlyCreated) {
                $this->info("Created permission: {$permission['name']}");
            } else {
                $this->line("Permission already exists: {$permission['name']}");
            }
        }

        // Assign all settings permissions to admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $settingsPermissions = Permission::where('module', 'settings')->pluck('id')->toArray();
            $adminRole->permissions()->syncWithoutDetaching($settingsPermissions);
            $this->info('Settings permissions assigned to admin role');
        }

        $this->info('Settings permissions setup completed!');
    }
}