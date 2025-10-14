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
        Schema::table('topics', function (Blueprint $table) {
            $table->text('description_ru')->nullable()->change();
            $table->text('description_pl')->nullable()->change();
            $table->text('description_by')->nullable()->change();
            $table->text('description_uk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->string('description_ru')->nullable()->change();
            $table->string('description_pl')->nullable()->change();
            $table->string('description_by')->nullable()->change();
            $table->string('description_uk')->nullable()->change();
        });
    }
};
