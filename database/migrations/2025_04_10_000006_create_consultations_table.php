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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null for guest consultations
            $table->text('skin_story'); // User's skin description
            $table->json('tags')->nullable(); // Manual tags added by user
            $table->json('detected_traits'); // AI-detected traits from text
            $table->string('concern_1')->nullable(); // Primary concern
            $table->string('concern_2')->nullable(); // Secondary concern
            $table->json('preferences')->nullable(); // User preferences (vegan, fragrance-free, etc)
            $table->json('recommendations')->nullable(); // AI-generated recommendations
            $table->enum('status', ['pending', 'processed', 'archived'])->default('pending');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->fullText('skin_story'); // For search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
