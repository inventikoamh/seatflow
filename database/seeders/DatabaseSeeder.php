<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@seatflow.com',
            'password' => Hash::make('password'),
            'theme_preference' => 'light',
        ]);

        // Create a test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@seatflow.com',
            'password' => Hash::make('password'),
            'theme_preference' => 'dark',
        ]);

        // Run other seeders
        $this->call([
            RolePermissionSeeder::class,
            LocationEventSeeder::class,
            AreasSeeder::class,
            MasjidGroundFloorSeatsSeeder::class,
            MasjidFirstFloorLadiesSeatsSeeder::class,
            MasjidSecondFloorLadiesSeatsSeeder::class,
            SehanFirstFloorLadiesSeatsSeeder::class,
            SehanGroundFloorGentsSeatsSeeder::class,
            TayyebiLeftLadiesSeatsSeeder::class,
            TayyebiCenterGentsSeatsSeeder::class,
            TayyebiRightLadiesOnChairSeatsSeeder::class,
        ]);

        // Ensure admin user has admin role
        $adminUser = User::where('email', 'admin@seatflow.com')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        
        if ($adminUser && $adminRole && !$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}
