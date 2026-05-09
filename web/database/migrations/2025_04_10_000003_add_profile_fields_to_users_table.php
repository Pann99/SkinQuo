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
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom baru untuk profil lengkap user
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('mobile_number')->nullable()->unique()->after('email');
            $table->date('birth_date')->nullable()->after('mobile_number');
            $table->enum('gender', ['female', 'male', 'non_binary', 'prefer_not'])->nullable()->after('birth_date');
            $table->string('avatar')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'mobile_number', 'birth_date', 'gender', 'avatar']);
        });
    }
};
