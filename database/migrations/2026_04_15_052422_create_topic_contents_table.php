<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id')
                ->unique()
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->longText('content_html');

            $table->longText('shortcut_tips')->nullable();

            $table->string('video_url')->nullable();

            $table->string('diagram_url')->nullable();

            $table->integer('estimated_read_minutes')
                ->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_contents');
    }
};
