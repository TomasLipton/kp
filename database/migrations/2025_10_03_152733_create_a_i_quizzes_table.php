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
        Schema::create('a_i_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            // Quiz settings
            $table->string('speed')->default('normal'); // slow, normal, fast
            $table->string('difficulty')->default('medium'); // easy, medium, hard
            $table->string('gender')->default('female'); // male, female
            $table->string('voice')->nullable(); // TTS voice identifier

            // OpenAI Realtime API keys
            $table->string('ephemeral_key')->nullable();
            $table->integer('ephemeral_key_expiry')->nullable(); // Unix timestamp

            // Quiz status
            $table->string('status')->default('preparing'); // preparing, in_progress, completed

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_i_quizzes');
    }
};
