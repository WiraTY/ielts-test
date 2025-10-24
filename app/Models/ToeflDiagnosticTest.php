<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToeflDiagnosticTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'is_active',
        'section_weights',
        'score_ranges',
        'recommendations'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'section_weights' => 'array',
        'score_ranges' => 'array'
    ];

    const SECTIONS = ['reading', 'listening', 'speaking', 'writing'];

    /**
     * Get the questions for the diagnostic test.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ToeflDiagnosticQuestion::class);
    }

    /**
     * Get the attempts for the diagnostic test.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(ToeflDiagnosticAttempt::class);
    }

    /**
     * Get questions by section.
     */
    public function questionsBySection(string $section): HasMany
    {
        return $this->questions()->where('section', $section);
    }

    /**
     * Get questions grouped by section.
     */
    public function getQuestionsBySection(): array
    {
        $grouped = [];

        foreach (self::SECTIONS as $section) {
            $grouped[$section] = $this->questionsBySection($section)
                ->orderBy('order')
                ->get();
        }

        return $grouped;
    }

    /**
     * Get the total possible score.
     */
    public function getTotalPossibleScore(): int
    {
        return $this->questions()->sum('score');
    }

    /**
     * Get the total possible score for each section.
     */
    public function getSectionPossibleScores(): array
    {
        $scores = [];

        foreach (self::SECTIONS as $section) {
            $scores[$section] = $this->questionsBySection($section)->sum('score');
        }

        return $scores;
    }

    /**
     * Get the default section weights if not set.
     */
    public function getSectionWeightsAttribute($value): array
    {
        if ($value) {
            return json_decode($value, true);
        }

        // Default equal weighting for all sections
        return [
            'reading' => 0.25,
            'listening' => 0.25,
            'speaking' => 0.25,
            'writing' => 0.25
        ];
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDuration(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%d hour%s %d minute%s',
                $hours, $hours > 1 ? 's' : '',
                $minutes, $minutes > 1 ? 's' : ''
            );
        }

        return sprintf('%d minute%s', $minutes, $minutes > 1 ? 's' : '');
    }

    /**
     * Calculate proficiency level based on score.
     */
    public static function calculateProficiencyLevel(int $score): array
    {
        if ($score >= 110) {
            return ['level' => 'Expert', 'description' => 'CEFR C2 - Can use English fluently and spontaneously'];
        } elseif ($score >= 95) {
            return ['level' => 'Very Good', 'description' => 'CEFR C1 - Can use English effectively for professional purposes'];
        } elseif ($score >= 80) {
            return ['level' => 'Good', 'description' => 'CEFR B2 - Can use English effectively and independently'];
        } elseif ($score >= 65) {
            return ['level' => 'Fair', 'description' => 'CEFR B1 - Can use English in familiar situations'];
        } elseif ($score >= 50) {
            return ['level' => 'Limited', 'description' => 'CEFR A2 - Can communicate in basic English'];
        } else {
            return ['level' => 'Very Limited', 'description' => 'CEFR A1 - Can understand and use familiar phrases'];
        }
    }

    /**
     * Generate recommendations based on diagnostic results.
     */
    public static function generateRecommendations(array $sectionScores): array
    {
        $recommendations = [];
        $totalScore = array_sum($sectionScores);

        foreach ($sectionScores as $section => $score) {
            if ($score < 20) { // Less than 67% of max score
                $recommendations[$section] = self::getSectionRecommendations($section, 'low');
            } elseif ($score < 25) { // Less than 83% of max score
                $recommendations[$section] = self::getSectionRecommendations($section, 'medium');
            } else {
                $recommendations[$section] = self::getSectionRecommendations($section, 'high');
            }
        }

        return [
            'overall' => self::getOverallRecommendations($totalScore),
            'sections' => $recommendations
        ];
    }

    /**
     * Get section-specific recommendations.
     */
    private static function getSectionRecommendations(string $section, string $level): array
    {
        $recommendations = [
            'reading' => [
                'low' => [
                    'Focus on vocabulary building exercises',
                    'Practice identifying main ideas and supporting details',
                    'Work on speed reading techniques',
                    'Study academic reading strategies'
                ],
                'medium' => [
                    'Practice inference and rhetorical purpose questions',
                    'Work on prose summary and table completion',
                    'Expand academic vocabulary',
                    'Time management practice'
                ],
                'high' => [
                    'Challenge with complex academic texts',
                    'Practice advanced inference questions',
                    'Focus on speed and accuracy',
                    'Master all question types'
                ]
            ],
            'listening' => [
                'low' => [
                    'Basic listening comprehension exercises',
                    'Practice understanding main ideas',
                    'Vocabulary in context',
                    'Note-taking techniques'
                ],
                'medium' => [
                    'Academic lecture practice',
                    'Understanding speaker attitude and function',
                    'Connection questions',
                    'Advanced note-taking'
                ],
                'high' => [
                    'Complex academic content',
                    'Multiple speaker conversations',
                    'Inference and organization questions',
                    'Speed listening comprehension'
                ]
            ],
            'speaking' => [
                'low' => [
                    'Basic pronunciation practice',
                    'Simple response structures',
                    'Speaking confidence building',
                    'Basic vocabulary usage'
                ],
                'medium' => [
                    'Integrated speaking tasks',
                    'Note-taking for speaking',
                    'Response organization',
                    'Fluency and coherence practice'
                ],
                'high' => [
                    'Complex integrated tasks',
                    'Advanced vocabulary and grammar',
                    'Natural delivery and intonation',
                    'Comprehensive response development'
                ]
            ],
            'writing' => [
                'low' => [
                    'Basic sentence structure',
                    'Simple essay organization',
                    'Grammar and punctuation basics',
                    'Paragraph development'
                ],
                'medium' => [
                    'Integrated writing practice',
                    'Academic essay structure',
                    'Complex sentence structures',
                    'Supporting evidence use'
                ],
                'high' => [
                    'Advanced academic writing',
                    'Sophisticated vocabulary',
                    'Complex argument development',
                    'Perfect grammar and mechanics'
                ]
            ]
        ];

        return $recommendations[$section][$level] ?? [];
    }

    /**
     * Get overall recommendations based on total score.
     */
    private static function getOverallRecommendations(int $totalScore): array
    {
        if ($totalScore >= 95) {
            return [
                'You have excellent English skills',
                'Focus on maintaining proficiency',
                'Consider taking official TOEFL test',
                'Advanced academic English practice recommended'
            ];
        } elseif ($totalScore >= 80) {
            return [
                'Good foundation in English',
                'Practice with challenging materials',
                'Focus on weak areas identified',
                'Regular practice sessions recommended'
            ];
        } elseif ($totalScore >= 65) {
            return [
                'Developing English skills',
                'Consistent daily practice needed',
                'Focus on vocabulary and grammar',
                'Consider structured learning program'
            ];
        } else {
            return [
                'Foundation English building needed',
                'Start with basic English lessons',
                'Regular vocabulary building essential',
                'Consider beginner English courses'
            ];
        }
    }
}