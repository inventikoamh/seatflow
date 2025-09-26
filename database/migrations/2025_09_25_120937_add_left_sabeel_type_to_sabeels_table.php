<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sabeels', function (Blueprint $table) {
            // Modify the sabeel_type enum to include 'left_sabeel'
            $table->enum('sabeel_type', ['regular', 'student', 'res_without_sabeel', 'left_sabeel'])->default('regular')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sabeels', function (Blueprint $table) {
            // Revert the sabeel_type enum to original values
            $table->enum('sabeel_type', ['regular', 'student', 'res_without_sabeel'])->default('regular')->change();
        });
    }
};
