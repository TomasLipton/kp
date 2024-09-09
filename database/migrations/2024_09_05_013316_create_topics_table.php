<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name_ru');
            $table->string('description_ru');
            $table->string('name_pl');
            $table->string('description_pl');
            $table->string('picture');
            $table->foreignId('parent_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
