<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topic_question_map', function (Blueprint $table) {

            if (!Schema::hasColumn('topic_question_map', 'topic_id')) {
                $table->foreignId('topic_id')
                    ->after('id')
                    ->constrained('topics')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('topic_question_map', 'question_id')) {
                $table->foreignId('question_id')
                    ->after('topic_id')
                    ->constrained('questions')
                    ->cascadeOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::table('topic_question_map', function (Blueprint $table) {

            if (Schema::hasColumn('topic_question_map', 'topic_id')) {
                $table->dropForeign(['topic_id']);
            }

            if (Schema::hasColumn('topic_question_map', 'question_id')) {
                $table->dropForeign(['question_id']);
            }

            try {
                $table->dropUnique(['topic_id', 'question_id']);
            } catch (\Throwable $e) {
                //
            }

            if (Schema::hasColumn('topic_question_map', 'topic_id')) {
                $table->dropColumn('topic_id');
            }

            if (Schema::hasColumn('topic_question_map', 'question_id')) {
                $table->dropColumn('question_id');
            }

        });
    }
};
