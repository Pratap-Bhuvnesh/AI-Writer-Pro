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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();

            $table->enum('discount_type', ['percentage', 'fixed']);

            $table->decimal('discount_value', 10, 2);

            $table->timestamp('expiry_date')->nullable();

            $table->unsignedInteger('usage_limit')->nullable(); // total usage limit

            $table->unsignedInteger('used_count')->default(0); // track usage

            $table->boolean('is_active')->default(true); // enable/disable

            $table->timestamps();

            // Index
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
