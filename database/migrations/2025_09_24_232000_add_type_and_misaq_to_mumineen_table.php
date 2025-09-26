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
        Schema::table('mumineen', function (Blueprint $table) {
            $table->enum('type', ['mehmaan', 'resident'])->default('resident')->after('mobile_number');
            $table->enum('misaq', ['done', 'not_done'])->default('not_done')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mumineen', function (Blueprint $table) {
            $table->dropColumn(['type', 'misaq']);
        });
    }
};
