<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->char('a_i_quiz_id', 36);
            $table->enum('role', ['system', 'user', 'assistant', 'tool', 'developer']);
            $table->text('content')->nullable();
            $table->string('tool_name')->nullable();
            $table->json('tool_call')->nullable(); // e.g. arguments, function name, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('a_i_quiz_id')->references('id')->on('a_i_quizzes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
