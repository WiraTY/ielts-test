<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'options',
        'answer_key',
        'score',
        'toefl_section',
        'toefl_question_type',
        'time_limit_seconds',
        'preparation_time_notes',
        'scoring_rubric',
        'sample_answer'
    ];

    protected $casts = [
        'options' => 'array',
        'answer_key' => 'array',
        'scoring_rubric' => 'array',
        'sample_answer' => 'array',
        'score' => 'integer',
        'time_limit_seconds' => 'integer'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // ========== TOEFL-Specific Methods ==========

    /**
     * Check if this is a TOEFL question
     */
    public function isToeflQuestion(): bool
    {
        return !is_null($this->toefl_section);
    }

    /**
     * Get TOEFL section display name
     */
    public function getToeflSectionDisplayName(): string
    {
        $sections = [
            'reading' => 'Reading',
            'listening' => 'Listening',
            'speaking' => 'Speaking',
            'writing' => 'Writing'
        ];

        return $sections[$this->toefl_section] ?? 'General';
    }

    /**
     * Get TOEFL question type display name
     */
    public function getToeflQuestionTypeDisplayName(): string
    {
        $types = [
            // Reading question types
            'reading_factual_information' => 'Factual Information',
            'reading_negative_factual_information' => 'Negative Factual Information',
            'reading_inference' => 'Inference',
            'reading_rhetorical_purpose' => 'Rhetorical Purpose',
            'reading_vocabulary' => 'Vocabulary',
            'reading_reference' => 'Reference',
            'reading_sentence_insertion' => 'Sentence Insertion',
            'reading_prose_summary' => 'Prose Summary',
            'reading_fill_in_table' => 'Fill in Table',
            'reading_complete_summary' => 'Complete Summary',

            // Listening question types
            'listening_gist_content' => 'Gist Content',
            'listening_gist_purpose' => 'Gist Purpose',
            'listening_detail' => 'Detail',
            'listening_function' => 'Function',
            'listening_attitude' => 'Attitude',
            'listening_organization' => 'Organization',
            'listening_connecting_content' => 'Connecting Content',
            'listening_inference' => 'Inference',

            // Speaking question types
            'speaking_independent_personal_preference' => 'Independent - Personal Preference',
            'speaking_independent_choice' => 'Independent - Choice',
            'speaking_integrated_campus_situation' => 'Integrated - Campus Situation',
            'speaking_integrated_academic_course' => 'Integrated - Academic Course',
            'speaking_integrated_reading_listening' => 'Integrated - Reading & Listening',

            // Writing question types
            'writing_integrated_reading_listening' => 'Integrated - Reading & Listening',
            'writing_independent_essay' => 'Independent Essay'
        ];

        return $types[$this->toefl_question_type] ?? 'General Question';
    }

    /**
     * Get the time limit for answering this question
     */
    public function getTimeLimit(): int
    {
        if ($this->time_limit_seconds) {
            return $this->time_limit_seconds;
        }

        // Default time limits by section
        $defaultLimits = [
            'reading' => 90,
            'listening' => 60,
            'speaking' => 60,
            'writing' => 1800
        ];

        return $defaultLimits[$this->toefl_section] ?? 120;
    }

    /**
     * Get the preparation time (mainly for speaking questions)
     */
    public function getPreparationTime(): int
    {
        if ($this->preparation_time_notes) {
            return (int) $this->preparation_time_notes;
        }

        // Default preparation times by section
        $defaultPrepTime = [
            'speaking' => 15,
            'writing' => 180
        ];

        return $defaultPrepTime[$this->toefl_section] ?? 0;
    }

    /**
     * Check if this question requires manual evaluation
     */
    public function requiresManualEvaluation(): bool
    {
        return in_array($this->toefl_section, ['speaking', 'writing']);
    }

    /**
     * Check if this is a multiple choice question
     */
    public function isMultipleChoice(): bool
    {
        return $this->type === 'mcq' && !empty($this->options);
    }

    /**
     * Get formatted options for display
     */
    public function getFormattedOptions(): array
    {
        if (!$this->options || !is_array($this->options)) {
            return [];
        }

        $formatted = [];
        $index = 0;

        foreach ($this->options as $key => $option) {
            $letter = chr(65 + $index); // A, B, C, D, etc.
            $formatted[$letter] = $option;
            $index++;
        }

        return $formatted;
    }

    /**
     * Get the correct answer for multiple choice questions
     */
    public function getCorrectAnswer(): ?string
    {
        if ($this->isMultipleChoice() && $this->answer_key) {
            return $this->answer_key['correct_answer'] ?? null;
        }

        return null;
    }

    /**
     * Get the rubric for evaluation
     */
    public function getEvaluationRubric(): array
    {
        if ($this->scoring_rubric) {
            return $this->scoring_rubric;
        }

        // Default rubrics by question type
        if ($this->requiresManualEvaluation()) {
            return [
                'content' => 'Development of ideas and relevance',
                'organization' => 'Clarity and structure',
                'language' => 'Grammar, vocabulary, and fluency',
                'task_completion' => 'Completion of all requirements'
            ];
        }

        return [];
    }

    /**
     * Get sample answer for reference
     */
    public function getSampleAnswer(): ?string
    {
        if ($this->sample_answer) {
            return $this->sample_answer['text'] ?? null;
        }

        return null;
    }

    /**
     * Get question instructions based on TOEFL type
     */
    public function getQuestionInstructions(): string
    {
        $instructions = '';

        if ($this->toefl_section === 'speaking') {
            if (strpos($this->toefl_question_type, 'integrated') !== false) {
                $instructions = 'Read the passage and listen to the audio, then respond to the question.';
            } else {
                $instructions = 'Please provide a complete and thoughtful response to the question.';
            }

            if ($this->getPreparationTime() > 0) {
                $instructions .= " You will have {$this->getPreparationTime()} seconds to prepare and {$this->getTimeLimit()} seconds to speak.";
            }
        } elseif ($this->toefl_section === 'writing') {
            if (strpos($this->toefl_question_type, 'integrated') !== false) {
                $instructions = 'Read the passage and listen to the audio, then write a response.';
            } else {
                $instructions = 'Write a well-developed essay on the given topic.';
            }
        } elseif ($this->toefl_section === 'reading') {
            $instructions = 'Read the passage carefully and choose the best answer.';
        } elseif ($this->toefl_section === 'listening') {
            $instructions = 'Listen to the audio carefully and choose the best answer.';
        }

        return $instructions;
    }
}
