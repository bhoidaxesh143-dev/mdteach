<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_topic_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->boolean('is_completed')
                ->default(false);

            $table->integer('time_spent_seconds')
                ->default(0);

            $table->timestamp('last_read_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'topic_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_topic_progress');
    }
};
