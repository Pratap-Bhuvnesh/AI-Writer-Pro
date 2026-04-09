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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('address_line1');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code', 20);
            $table->string('country')->default('India');

            $table->boolean('is_default')->default(false); // ⭐ useful
            $table->string('phone')->nullable();
            $table->enum('type', ['home', 'office']);
            $table->json('meta')->nullable();
            $table->timestamps();

            // Index
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addreses');
    }
};
