<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class CheckQuestionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-question-data {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check question data by ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $question = Question::find($id);
        
        if ($question) {
            $this->info('Question found:');
            $this->line('ID: ' . $question->id);
            $this->line('Quiz ID: ' . $question->quiz_id);
            $this->line('Type: ' . $question->type);
            $this->line('Question Text: ' . $question->question_text);
            $this->line('Options: ' . json_encode($question->options));
            $this->line('Answer Key: ' . json_encode($question->answer_key));
            $this->line('Score: ' . $question->score);
        } else {
            $this->error('Question not found');
        }
    }
}
