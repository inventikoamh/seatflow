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
        Schema::create('mumineen', function (Blueprint $table) {
            $table->id();
            $table->string('ITS_ID', 8)->unique();
            $table->string('full_name');
            $table->string('sabeel_code');
            $table->string('mobile_number', 15)->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('sabeel_code')->references('sabeel_code')->on('sabeels')->onDelete('cascade');
            
            // Indexes
            $table->index('sabeel_code');
            $table->index('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mumineen');
    }
};
