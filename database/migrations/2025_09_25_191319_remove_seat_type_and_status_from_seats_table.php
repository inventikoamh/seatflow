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
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['status', 'seat_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->enum('status', ['available', 'occupied', 'reserved', 'blocked', 'maintenance'])->default('available');
            $table->enum('seat_type', ['regular', 'premium', 'accessible', 'vip'])->default('regular');
        });
    }
};