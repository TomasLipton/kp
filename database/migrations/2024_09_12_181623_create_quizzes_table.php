<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->nullable();
            $table->string('type')->nullable();
            $table->unsignedTinyInteger('questions_amount');
            $table->boolean('is_completed');
            $table->foreignId('topics_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
