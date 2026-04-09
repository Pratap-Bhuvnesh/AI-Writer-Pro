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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('payment_method', ['upi', 'card', 'cod']);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed', 'refunded'
            ])->default('pending');

            $table->string('transaction_id')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->unique('transaction_id');
            $table->json('gateway_response')->nullable();

            // Indexes
            $table->index('order_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
