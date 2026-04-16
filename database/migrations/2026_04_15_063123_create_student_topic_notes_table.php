<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_topic_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->longText('note');

            $table->timestamps();

            $table->unique([
                'student_id',
                'topic_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_topic_notes');
    }
};
