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
         Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();

            // FK → users table (admin user)
            $table->foreignId('admin_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('action'); // e.g. created, updated, deleted

            // Polymorphic-like fields
            $table->string('entity_type'); // product, order, user, etc.
            $table->unsignedBigInteger('entity_id');

            $table->timestamp('created_at')->useCurrent();

            // Indexes for performance
            $table->index('admin_id');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
