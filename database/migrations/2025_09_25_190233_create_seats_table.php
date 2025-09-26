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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->integer('seat_number');
            $table->integer('row_number');
            $table->integer('column_number');
            $table->string('column_label', 3)->nullable(); // A, B, C, ..., BC
            $table->enum('status', ['available', 'occupied', 'reserved', 'blocked', 'maintenance'])->default('available');
            $table->enum('seat_type', ['regular', 'premium', 'accessible', 'vip'])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['area_id', 'seat_number']);
            $table->index(['area_id', 'row_number', 'column_number']);
            $table->index(['area_id', 'status']);
            $table->unique(['area_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};