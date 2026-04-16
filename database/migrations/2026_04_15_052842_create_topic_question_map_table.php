<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_question_map', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'topic_id',
                'question_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_question_map');
    }
};
