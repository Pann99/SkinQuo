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
        Schema::table('products', function (Blueprint $table) {
            // Add is_best_seller column if it doesn't exist
            if (!Schema::hasColumn('products', 'is_best_seller')) {
                $table->boolean('is_best_seller')->default(false)->after('link_produk');
            }
            
            // Add sold_count column if it doesn't exist
            if (!Schema::hasColumn('products', 'sold_count')) {
                $table->integer('sold_count')->default(0)->after('is_best_seller');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_best_seller')) {
                $table->dropColumn('is_best_seller');
            }
            
            if (Schema::hasColumn('products', 'sold_count')) {
                $table->dropColumn('sold_count');
            }
        });
    }
};
