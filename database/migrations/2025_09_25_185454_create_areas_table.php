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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('capacity')->default(0);
            $table->enum('gender_type', ['male', 'female', 'mixed'])->default('mixed');
            $table->integer('floor')->default(1);
            $table->string('section')->nullable();
            $table->enum('event_type', ['ramzaan', 'ashara', 'both'])->default('both');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['location_id', 'event_type']);
            $table->index(['location_id', 'gender_type']);
            $table->index(['location_id', 'floor']);
            $table->unique(['location_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};