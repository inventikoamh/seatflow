<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define specific gates for each permission
        $permissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users', 'manage-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles', 'manage-roles',
            'view-sabeels', 'create-sabeels', 'edit-sabeels', 'delete-sabeels', 'manage-sabeels',
            'view-mumineen', 'create-mumineen', 'edit-mumineen', 'delete-mumineen', 'manage-mumineen',
            'view-locations', 'create-locations', 'edit-locations', 'delete-locations', 'manage-locations',
            'view-events', 'create-events', 'edit-events', 'delete-events', 'manage-events',
            'view-takhmeen', 'create-takhmeen', 'edit-takhmeen', 'delete-takhmeen', 'manage-takhmeen',
            'view-noc', 'create-noc', 'edit-noc', 'delete-noc', 'manage-noc',
            'view-settings', 'manage-migrations', 'manage-seeders', 'manage-storage', 'manage-settings', // Added Settings permissions
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}