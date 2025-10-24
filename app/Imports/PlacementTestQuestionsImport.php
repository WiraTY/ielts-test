<?php

namespace App\Imports;

use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class PlacementTestQuestionsImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation
{
    protected $placementTest;

    public function __construct(PlacementTest $placementTest)
    {
        $this->placementTest = $placementTest;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $rowIndex => $row) {
            // Skip empty rows
            if (empty($row['question_text'])) {
                continue;
            }

            // Validate required fields
            if (empty($row['option_a']) || empty($row['option_b']) || 
                empty($row['option_c']) || empty($row['option_d'])) {
                throw new \Exception("Row " . ($rowIndex + 2) . ": All options (A, B, C, D) are required.");
            }

            if (empty($row['correct_answer'])) {
                throw new \Exception("Row " . ($rowIndex + 2) . ": Correct answer is required.");
            }

            // Validate correct answer is one of A, B, C, D
            $validAnswers = ['A', 'B', 'C', 'D'];
            if (!in_array($row['correct_answer'], $validAnswers)) {
                throw new \Exception("Row " . ($rowIndex + 2) . ": Correct answer must be one of A, B, C, or D.");
            }

            // Prepare options array
            $options = [
                'A' => $row['option_a'] ?? '',
                'B' => $row['option_b'] ?? '',
                'C' => $row['option_c'] ?? '',
                'D' => $row['option_d'] ?? '',
            ];

            // Create the question
            $this->placementTest->questions()->create([
                'question_text' => $row['question_text'],
                'options' => $options,
                'correct_answer' => $row['correct_answer'],
                'score' => $row['score'] ?? 1,
                'order' => $row['order'] ?? 0,
            ]);
        }
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'question_text' => 'required|string|max:1000',
            'option_a' => 'required|string|max:500',
            'option_b' => 'required|string|max:500',
            'option_c' => 'required|string|max:500',
            'option_d' => 'required|string|max:500',
            'correct_answer' => 'required|in:A,B,C,D',
            'score' => 'nullable|integer|min:1|max:10',
            'order' => 'nullable|integer|min:0|max:1000',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'question_text.required' => 'Question text is required',
            'option_a.required' => 'Option A is required',
            'option_b.required' => 'Option B is required',
            'option_c.required' => 'Option C is required',
            'option_d.required' => 'Option D is required',
            'correct_answer.required' => 'Correct answer is required',
            'correct_answer.in' => 'Correct answer must be A, B, C, or D',
            'score.integer' => 'Score must be a number',
            'score.min' => 'Score must be at least 1',
            'score.max' => 'Score cannot be more than 10',
            'order.integer' => 'Order must be a number',
            'order.min' => 'Order cannot be negative',
            'order.max' => 'Order cannot be more than 1000',
        ];
    }
}