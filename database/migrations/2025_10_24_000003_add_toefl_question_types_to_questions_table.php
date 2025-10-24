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
        Schema::table('questions', function (Blueprint $table) {
            $table->enum('toefl_section', ['reading', 'listening', 'speaking', 'writing'])->nullable()->after('type');
            $table->enum('toefl_question_type', [
                // Reading question types
                'reading_factual_information',
                'reading_negative_factual_information',
                'reading_inference',
                'reading_rhetorical_purpose',
                'reading_vocabulary',
                'reading_reference',
                'reading_sentence_insertion',
                'reading_prose_summary',
                'reading_fill_in_table',
                'reading_complete_summary',

                // Listening question types
                'listening_gist_content',
                'listening_gist_purpose',
                'listening_detail',
                'listening_function',
                'listening_attitude',
                'listening_organization',
                'listening_connecting_content',
                'listening_inference',

                // Speaking question types
                'speaking_independent_personal_preference',
                'speaking_independent_choice',
                'speaking_integrated_campus_situation',
                'speaking_integrated_academic_course',
                'speaking_integrated_reading_listening',

                // Writing question types
                'writing_integrated_reading_listening',
                'writing_independent_essay'
            ])->nullable()->after('toefl_section');

            $table->integer('time_limit_seconds')->nullable()->after('score');
            $table->text('preparation_time_notes')->nullable()->after('time_limit_seconds');
            $table->text('scoring_rubric')->nullable()->after('answer_key');
            $table->json('sample_answer')->nullable()->after('scoring_rubric');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'toefl_section',
                'toefl_question_type',
                'time_limit_seconds',
                'preparation_time_notes',
                'scoring_rubric',
                'sample_answer'
            ]);
        });
    }
};