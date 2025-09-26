<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignAdminToFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:assign-first-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to the first user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        
        if (!$user) {
            $this->error("No users found.");
            return 1;
        }
        
        $adminRole = Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $this->error("Admin role not found.");
            return 1;
        }
        
        $user->assignRole($adminRole);
        
        $this->info("Admin role assigned to user: {$user->email}");
        
        return 0;
    }
}