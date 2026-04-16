<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('duration_minutes');

            $table->decimal('negative_marking', 5, 2)
                ->default(0.25);

            $table->integer('total_marks')->default(0);

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['created_by', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
