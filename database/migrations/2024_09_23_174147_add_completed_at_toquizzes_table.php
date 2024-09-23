<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('is_completed');
            $table->timestamp('completed_at')->after('questions_amount')->nullable();

        });
        $quizzes = \App\Models\Quiz::all();
        foreach ($quizzes as $quiz) {
            $quiz->completed_at = now();
            $quiz->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
