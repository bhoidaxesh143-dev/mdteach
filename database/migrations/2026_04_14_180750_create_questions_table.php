<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('question');

            $table->enum('type', [
                'single_choice',
                'multiple_choice'
            ])->default('single_choice');

            $table->decimal('marks', 5, 2)->default(1);

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['exam_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
