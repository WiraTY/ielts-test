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
        Schema::create('toefl_diagnostic_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(120); // Standard TOEFL duration
            $table->boolean('is_active')->default(true);
            $table->json('section_weights')->nullable(); // Weight for each section
            $table->json('score_ranges')->nullable(); // Score range mappings
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });

        Schema::create('toefl_diagnostic_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toefl_diagnostic_test_id')->constrained()->onDelete('cascade');
            $table->enum('section', ['reading', 'listening', 'speaking', 'writing']);
            $table->text('question_text');
            $table->json('options')->nullable(); // For multiple choice questions
            $table->text('correct_answer')->nullable();
            $table->integer('score')->default(1);
            $table->integer('order')->default(0);
            $table->text('explanation')->nullable();
            $table->json('rubric')->nullable(); // For speaking/writing questions
            $table->timestamps();

            $table->index(['toefl_diagnostic_test_id', 'section', 'order']);
        });

        Schema::create('toefl_diagnostic_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toefl_diagnostic_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->integer('reading_score')->default(0);
            $table->integer('listening_score')->default(0);
            $table->integer('speaking_score')->default(0);
            $table->integer('writing_score')->default(0);
            $table->integer('total_score')->virtualAs('reading_score + listening_score + speaking_score + writing_score');
            $table->enum('status', ['in_progress', 'completed', 'timeout'])->default('in_progress');
            $table->json('section_feedback')->nullable();
            $table->text('overall_feedback')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['toefl_diagnostic_test_id', 'user_id']);
        });

        Schema::create('toefl_diagnostic_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('toefl_diagnostic_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('toefl_diagnostic_questions')->onDelete('cascade');
            $table->text('answer');
            $table->boolean('is_correct')->nullable();
            $table->integer('score_awarded')->default(0);
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index(['attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toefl_diagnostic_answers');
        Schema::dropIfExists('toefl_diagnostic_attempts');
        Schema::dropIfExists('toefl_diagnostic_questions');
        Schema::dropIfExists('toefl_diagnostic_tests');
    }
};