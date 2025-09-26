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
        Schema::table('takhmeen', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'due_date',
                'paid_date',
                'paid_amount',
                'payment_method',
                'receipt_number',
                'is_active'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('takhmeen', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])->default('pending');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('receipt_number')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};