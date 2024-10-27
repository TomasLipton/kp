<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_pl');
            $table->string('question_ru')->nullable();
            $table->string('question_type');
            $table->string('picture')->nullable();
            $table->text('explanation_pl')->nullable();
            $table->text('explanation_ru')->nullable();
            $table->foreignId('topics_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
