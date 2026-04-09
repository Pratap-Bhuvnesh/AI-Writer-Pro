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
        Schema::create('shipments', function (Blueprint $table) {
            $table->foreignId('order_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('address_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('courier_name');
                $table->string('tracking_number')->unique();

                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('delivered_at')->nullable();

                $table->timestamps();

                // Indexes
                $table->index('order_id');
                $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
