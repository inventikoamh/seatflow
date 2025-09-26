<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update sabeel_sector enum to include new sectors
        DB::statement("ALTER TABLE sabeels MODIFY COLUMN sabeel_sector ENUM('ezzi', 'fakhri', 'hakimi', 'shujai', 'al_masjid_us_saifee', 'raj_township', 'zainy', 'student', 'mtnc', 'unknown')");
        
        // Update sabeel_type enum to include new types
        DB::statement("ALTER TABLE sabeels MODIFY COLUMN sabeel_type ENUM('regular', 'student', 'res_without_sabeel', 'moallemeen', 'regular_lock_joint', 'left_sabeel')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert sabeel_sector enum to original values
        DB::statement("ALTER TABLE sabeels MODIFY COLUMN sabeel_sector ENUM('ezzi', 'fakhri', 'hakimi', 'shujai')");
        
        // Revert sabeel_type enum to original values
        DB::statement("ALTER TABLE sabeels MODIFY COLUMN sabeel_type ENUM('regular', 'student', 'res_without_sabeel')");
    }
};