<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
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