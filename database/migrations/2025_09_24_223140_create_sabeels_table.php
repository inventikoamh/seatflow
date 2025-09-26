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
        Schema::create('sabeels', function (Blueprint $table) {
            $table->id();
            $table->string('sabeel_code')->unique();
            $table->text('sabeel_address');
            $table->enum('sabeel_sector', ['ezzi', 'fakhri', 'hakimi', 'shujai']);
            $table->string('sabeel_hof')->nullable(); // Head of Family ITS_ID
            $table->enum('sabeel_type', ['regular', 'student', 'res_without_sabeel'])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['sabeel_sector', 'is_active']);
            $table->index('sabeel_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sabeels');
    }
};
