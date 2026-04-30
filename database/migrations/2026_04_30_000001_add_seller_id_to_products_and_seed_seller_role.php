<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('seller_id')
                ->nullable()
                ->after('category_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('seller_id');
        });

        DB::table('roles')->updateOrInsert(
            ['name' => 'seller'],
            ['guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropIndex(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
};
