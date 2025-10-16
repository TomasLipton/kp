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
            $table->string('seo_description_pl', 160)->nullable()->after('description_pl');
            $table->string('seo_description_ru', 160)->nullable()->after('description_ru');
            $table->string('seo_description_by', 160)->nullable()->after('description_by');
            $table->string('seo_description_uk', 160)->nullable()->after('description_uk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn(['seo_description_pl', 'seo_description_ru', 'seo_description_by', 'seo_description_uk']);
        });
    }
};
