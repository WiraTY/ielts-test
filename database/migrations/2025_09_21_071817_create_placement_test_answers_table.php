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
        Schema::create('placement_test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('placement_test_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('placement_test_questions')->onDelete('cascade');
            $table->string('selected_answer'); // Student's selected option
            $table->boolean('is_correct')->nullable();
            $table->integer('score_awarded')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_test_answers');
    }
};
