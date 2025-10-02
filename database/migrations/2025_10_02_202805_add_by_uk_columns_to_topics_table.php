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
            $table->string('name_by')->after('name_pl');
            $table->string('description_by')->after('description_pl');
            $table->string('name_uk')->after('name_by');
            $table->string('description_uk')->after('description_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn(['name_by', 'description_by', 'name_uk', 'description_uk']);
        });
    }
};
