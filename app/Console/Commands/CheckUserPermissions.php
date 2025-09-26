<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;

class CheckUserPermissions extends Command
{
    protected $signature = 'check:user-permissions {email=admin@seatflow.com}';
    protected $description = 'Check user permissions for takhmeen and NOC';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found");
            return;
        }
        
        $this->info("Checking permissions for: {$user->email}");
        $this->line("Roles: " . $user->roles->pluck('name')->implode(', '));
        $this->line("");
        
        // Check takhmeen permissions
        $takhmeenPermissions = [
            'view-takhmeen',
            'create-takhmeen', 
            'edit-takhmeen',
            'delete-takhmeen',
            'manage-takhmeen'
        ];
        
        $this->info("Takhmeen Permissions:");
        foreach ($takhmeenPermissions as $permission) {
            $hasPermission = $user->hasPermission($permission);
            $status = $hasPermission ? '✓' : '✗';
            $this->line("  {$status} {$permission}");
        }
        
        $this->line("");
        
        // Check NOC permissions
        $nocPermissions = [
            'view-noc',
            'create-noc',
            'edit-noc', 
            'delete-noc',
            'manage-noc'
        ];
        
        $this->info("NOC Permissions:");
        foreach ($nocPermissions as $permission) {
            $hasPermission = $user->hasPermission($permission);
            $status = $hasPermission ? '✓' : '✗';
            $this->line("  {$status} {$permission}");
        }
        
        $this->line("");
        
        // Check if menus should be visible
        $takhmeenVisible = $user->hasPermission('view-takhmeen') || 
                          $user->hasPermission('create-takhmeen') || 
                          $user->hasPermission('edit-takhmeen') || 
                          $user->hasPermission('delete-takhmeen') || 
                          $user->hasPermission('manage-takhmeen');
                          
        $nocVisible = $user->hasPermission('view-noc') || 
                     $user->hasPermission('create-noc') || 
                     $user->hasPermission('edit-noc') || 
                     $user->hasPermission('delete-noc') || 
                     $user->hasPermission('manage-noc');
        
        $this->info("Menu Visibility:");
        $this->line("  Takhmeen menu: " . ($takhmeenVisible ? '✓ Visible' : '✗ Hidden'));
        $this->line("  NOC menu: " . ($nocVisible ? '✓ Visible' : '✗ Hidden'));
    }
}