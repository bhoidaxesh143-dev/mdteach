<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('started_at');

            $table->timestamp('submitted_at')->nullable();

            $table->decimal('score', 8, 2)->nullable();

            $table->enum('status', [
                'in_progress',
                'submitted',
                'expired'
            ])->default('in_progress');

            $table->timestamps();

            $table->index(['student_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
