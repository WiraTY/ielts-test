<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToeflDiagnosticQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'toefl_diagnostic_test_id',
        'section',
        'question_text',
        'options',
        'correct_answer',
        'score',
        'order',
        'explanation',
        'rubric'
    ];

    protected $casts = [
        'options' => 'array',
        'score' => 'integer',
        'order' => 'integer',
        'rubric' => 'array'
    ];

    const SECTIONS = ['reading', 'listening', 'speaking', 'writing'];

    /**
     * Get the diagnostic test that owns the question.
     */
    public function diagnosticTest(): BelongsTo
    {
        return $this->belongsTo(ToeflDiagnosticTest::class);
    }

    /**
     * Get the answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(ToeflDiagnosticAnswer::class);
    }

    /**
     * Get the correct answer for multiple choice questions.
     */
    public function getCorrectOption(): ?array
    {
        if (!$this->options || !$this->correct_answer) {
            return null;
        }

        $optionIndex = ord(strtoupper($this->correct_answer)) - ord('A');
        $options = array_values($this->options);

        return $options[$optionIndex] ?? null;
    }

    /**
     * Check if the provided answer is correct.
     */
    public function isAnswerCorrect(string $answer): bool
    {
        // For speaking and writing questions, manual evaluation is needed
        if (in_array($this->section, ['speaking', 'writing'])) {
            return null; // Cannot auto-evaluate
        }

        return strtoupper(trim($answer)) === strtoupper(trim($this->correct_answer));
    }

    /**
     * Get the question type based on section.
     */
    public function getQuestionType(): string
    {
        switch ($this->section) {
            case 'reading':
                return $this->getReadingQuestionType();
            case 'listening':
                return $this->getListeningQuestionType();
            case 'speaking':
                return $this->getSpeakingQuestionType();
            case 'writing':
                return $this->getWritingQuestionType();
            default:
                return 'unknown';
        }
    }

    /**
     * Get reading question type based on question characteristics.
     */
    private function getReadingQuestionType(): string
    {
        $text = strtolower($this->question_text);

        if (strpos($text, 'according to') !== false || strpos($text, 'what') !== false) {
            return 'Factual Information';
        } elseif (strpos($text, 'not') !== false || strpos($text, 'except') !== false) {
            return 'Negative Factual Information';
        } elseif (strpos($text, 'infer') !== false || strpos($text, 'imply') !== false) {
            return 'Inference';
        } elseif (strpos($text, 'purpose') !== false || strpos($text, 'why') !== false) {
            return 'Rhetorical Purpose';
        } elseif (strpos($text, 'refers to') !== false || strpos($text, 'the word') !== false) {
            return 'Reference';
        } else {
            return 'Factual Information';
        }
    }

    /**
     * Get listening question type based on question characteristics.
     */
    private function getListeningQuestionType(): string
    {
        $text = strtolower($this->question_text);

        if (strpos($text, 'mainly') !== false || strpos($text, 'topic') !== false) {
            return 'Gist Content';
        } elseif (strpos($text, 'purpose') !== false || strpos($text, 'why') !== false) {
            return 'Gist Purpose';
        } elseif (strpos($text, 'according to') !== false) {
            return 'Detail';
        } elseif (strpos($text, 'attitude') !== false || strpos($text, 'opinion') !== false) {
            return 'Attitude';
        } else {
            return 'Detail';
        }
    }

    /**
     * Get speaking question type.
     */
    private function getSpeakingQuestionType(): string
    {
        if ($this->question_text && strpos($this->question_text, 'reading') !== false) {
            return 'Integrated Task';
        }
        return 'Independent Task';
    }

    /**
     * Get writing question type.
     */
    private function getWritingQuestionType(): string
    {
        if ($this->question_text && strpos($this->question_text, 'reading') !== false) {
            return 'Integrated Task';
        }
        return 'Independent Essay';
    }

    /**
     * Get time limit for the question based on section and type.
     */
    public function getTimeLimitSeconds(): int
    {
        switch ($this->section) {
            case 'reading':
                return 90; // Average time per reading question
            case 'listening':
                return 60; // Average time per listening question
            case 'speaking':
                return 60; // Standard speaking response time
            case 'writing':
                return 1800; // 30 minutes for integrated writing
            default:
                return 120;
        }
    }

    /**
     * Get preparation time for the question (mainly for speaking).
     */
    public function getPreparationTimeSeconds(): int
    {
        switch ($this->section) {
            case 'speaking':
                return 15; // 15 seconds preparation for speaking
            case 'writing':
                return 180; // 3 minutes for integrated writing preparation
            default:
                return 0;
        }
    }
}