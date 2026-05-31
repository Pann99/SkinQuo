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
        Schema::table('feedback', function (Blueprint $table) {
            // Add is_reviewed column if it doesn't exist
            if (!Schema::hasColumn('feedback', 'is_reviewed')) {
                $table->boolean('is_reviewed')->default(false)->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            if (Schema::hasColumn('feedback', 'is_reviewed')) {
                $table->dropColumn('is_reviewed');
            }
        });
    }
};
