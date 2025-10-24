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
        Schema::create('placement_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_test_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->json('options'); // For MCQ - array of options
            $table->string('correct_answer'); // The correct option
            $table->integer('score')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_test_questions');
    }
};
