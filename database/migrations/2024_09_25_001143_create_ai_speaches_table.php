<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_speaches', function (Blueprint $table) {
            $table->id();
            $table->string('path_to_audio')->nullable();
            $table->string('type');
            $table->foreignId('question_id')->nullable();
            $table->foreignId('question_answer_id')->nullable();
            $table->string('voice_id');
            $table->string('text');
            $table->json('voice_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
