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
        Schema::create('noc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sabeel_id')->constrained('sabeels')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->text('remark')->nullable();
            $table->timestamp('noc_alloted_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate NOC for same sabeel and event
            $table->unique(['sabeel_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noc');
    }
};