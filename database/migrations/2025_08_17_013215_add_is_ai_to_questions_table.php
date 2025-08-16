<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', static function (Blueprint $table) {
            $table->boolean('is_reviewed')->default(true)->after('id');
        });
    }
};
