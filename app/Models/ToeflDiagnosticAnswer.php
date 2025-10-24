<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToeflDiagnosticAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer',
        'is_correct',
        'score_awarded',
        'feedback'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score_awarded' => 'integer'
    ];

    /**
     * Get the attempt that owns the answer.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ToeflDiagnosticAttempt::class, 'attempt_id');
    }

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(ToeflDiagnosticQuestion::class, 'question_id');
    }

    /**
     * Auto-evaluate the answer and set correctness and score.
     */
    public function autoEvaluate(): void
    {
        $question = $this->question;

        if (!$question) {
            return;
        }

        // For reading and listening questions, auto-evaluate
        if (in_array($question->section, ['reading', 'listening'])) {
            $this->is_correct = $question->isAnswerCorrect($this->answer);
            $this->score_awarded = $this->is_correct ? $question->score : 0;
        } else {
            // For speaking and writing, mark for manual evaluation
            $this->is_correct = null; // Cannot auto-evaluate
            $this->score_awarded = 0; // Will be set by manual evaluation
        }

        $this->save();
    }

    /**
     * Set manual evaluation for speaking/writing answers.
     */
    public function setManualEvaluation(bool $isCorrect, int $score, string $feedback = null): void
    {
        $this->is_correct = $isCorrect;
        $this->score_awarded = $score;
        if ($feedback) {
            $this->feedback = $feedback;
        }
        $this->save();
    }

    /**
     * Get the answer type based on question section.
     */
    public function getAnswerType(): string
    {
        $question = $this->question;
        return $question ? $question->section : 'unknown';
    }

    /**
     * Check if this answer requires manual evaluation.
     */
    public function requiresManualEvaluation(): bool
    {
        $question = $this->question;
        return $question ? in_array($question->section, ['speaking', 'writing']) : false;
    }

    /**
     * Get evaluation status.
     */
    public function getEvaluationStatus(): string
    {
        if ($this->requiresManualEvaluation()) {
            if ($this->score_awarded > 0) {
                return 'Manually Evaluated';
            }
            return 'Pending Evaluation';
        }
        return 'Auto Evaluated';
    }

    /**
     * Get formatted feedback for the answer.
     */
    public function getFormattedFeedback(): string
    {
        $question = $this->question;
        $feedback = '';

        // Add question explanation if available
        if ($question && $question->explanation) {
            $feedback .= "Explanation: {$question->explanation}\n\n";
        }

        // Add specific feedback if available
        if ($this->feedback) {
            $feedback .= "Feedback: {$this->feedback}";
        }

        // Add correctness information for auto-evaluated answers
        if (!$this->requiresManualEvaluation()) {
            if ($this->is_correct) {
                $feedback .= "Correct! Well done.";
            } else {
                $feedback .= "Incorrect. Review the explanation and try similar questions.";
            }
        }

        return trim($feedback);
    }
}