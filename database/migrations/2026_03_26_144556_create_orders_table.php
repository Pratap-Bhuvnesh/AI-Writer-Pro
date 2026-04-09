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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('total_amount', 10, 2);

            // Order status
            $table->enum('status', [
                'pending',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending');

            // Payment status
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed'
            ])->default('pending');
            $table->string('order_number')->unique();
            $table->text('shipping_address');
            $table->string('payment_method')->nullable();
            $table->timestamps();

            // Index for performance
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
