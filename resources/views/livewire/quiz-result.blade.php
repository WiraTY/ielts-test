<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $attempt->quiz->title }} - Results</h1>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-lg">
                            <span class="font-medium">Your Score:</span> 
                            <span class="font-bold text-xl">{{ $attempt->score }}</span>
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Passing Score:</span> {{ $attempt->quiz->pass_score }}
                        </p>
                    </div>
                    <div>
                        @if($attempt->score >= $attempt->quiz->pass_score)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Passed
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Failed
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <h2 class="text-xl font-bold text-gray-800 mb-4">Question Review</h2>
            
            <div class="space-y-6">
                @foreach($questions as $index => $question)
                    @php
                        $answer = $answers->firstWhere('question_id', $question->id);
                    @endphp
                    
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-medium text-gray-800">Question {{ $index + 1 }}</h3>
                            <span class="text-sm font-medium {{ $answer && $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                {{ $answer && $answer->is_correct ? 'Correct' : 'Incorrect' }}
                            </span>
                        </div>
                        
                        <div class="mb-3 font-medium">{!! $question->question_text !!}</div>
                        
                        <!-- Options Section -->
                        <div class="mb-3">
                            <p class="font-medium text-gray-700 mb-2">Options:</p>
                            <div class="ml-4 space-y-1">
                                @if($question->type === 'mcq' || $question->type === 'multi')
                                    @foreach($question->options as $optionIndex => $option)
                                        @php
                                            $isUserAnswer = false;
                                            $isCorrectAnswer = false;
                                            
                                            // Check if this is user's answer
                                            if ($answer) {
                                                if ($question->type === 'mcq' && isset($answer->answer['option'])) {
                                                    $isUserAnswer = $answer->answer['option'] == $optionIndex;
                                                } elseif ($question->type === 'multi' && isset($answer->answer['options'])) {
                                                    $userAnswers = $answer->answer['options'];
                                                    $isUserAnswer = is_array($userAnswers) && in_array($optionIndex, $userAnswers);
                                                }
                                            }
                                            
                                            // Check if this is correct answer
                                            if ($question->type === 'mcq') {
                                                $isCorrectAnswer = isset($question->answer_key['correct']) && $question->answer_key['correct'] == $optionIndex;
                                            } elseif ($question->type === 'multi') {
                                                $correctAnswers = $question->answer_key['correct'] ?? [];
                                                $isCorrectAnswer = is_array($correctAnswers) && in_array($optionIndex, $correctAnswers);
                                            }
                                        @endphp
                                        
                                        <div class="flex items-center">
                                            <span class="{{ $isCorrectAnswer ? 'text-green-600 font-bold' : ($isUserAnswer ? 'text-red-600' : '') }}">
                                                {{ chr(65 + $optionIndex) }}. {{ $option }}
                                                @if($isUserAnswer && !$isCorrectAnswer)
                                                    <span class="ml-2 text-red-600 font-bold">[Your Answer]</span>
                                                @endif
                                                @if($isCorrectAnswer)
                                                    <span class="ml-2 text-green-600 font-bold">[Correct]</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <!-- User's Answer Section -->
                        @if($answer && ($question->type === 'mcq' || $question->type === 'multi'))
                            <div class="mb-3">
                                <p class="font-medium text-gray-700 mb-2">Your Answer:</p>
                                <div class="ml-4">
                                    @if($question->type === 'mcq')
                                        @if($answer && isset($answer->answer) && isset($answer->answer['option']) && is_numeric($answer->answer['option']) && isset($question->options[$answer->answer['option']]))
                                            <p class="{{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                                {{ chr(65 + $answer->answer['option']) }}. {{ $question->options[$answer->answer['option']] }}
                                            </p>
                                        @endif
                                    @elseif($question->type === 'multi')
                                        @if($answer && isset($answer->answer) && isset($answer->answer['options']) && is_array($answer->answer['options']) && !empty($answer->answer['options']))
                                            <ul class="list-disc list-inside">
                                                @foreach($answer->answer['options'] as $optionIndex)
                                                    @if(is_numeric($optionIndex) && isset($question->options[$optionIndex]))
                                                        <li class="{{ in_array($optionIndex, $question->answer_key['correct'] ?? []) ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ chr(65 + $optionIndex) }}. {{ $question->options[$optionIndex] }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @elseif($answer && $question->type === 'essay')
                            <div class="mb-3">
                                <p class="font-medium text-gray-700 mb-2">Your Answer:</p>
                                <div class="ml-4">
                                    @if($answer && isset($answer->answer) && isset($answer->answer['text']))
                                        <p>{{ $answer->answer['text'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Correct Answer Section (only show for incorrect answers) -->
                        @if($answer && !$answer->is_correct && $question->type !== 'essay')
                            <div class="mb-3">
                                <p class="font-medium text-gray-700 mb-2">Correct Answer:</p>
                                <div class="ml-4">
                                    @if($question->type === 'mcq')
                                        @if(isset($question->answer_key['correct']) && is_numeric($question->answer_key['correct']) && isset($question->options[$question->answer_key['correct']]))
                                            <p class="text-green-600">
                                                {{ chr(65 + $question->answer_key['correct']) }}. {{ $question->options[$question->answer_key['correct']] }}
                                            </p>
                                        @endif
                                    @elseif($question->type === 'multi')
                                        @if(isset($question->answer_key['correct']) && is_array($question->answer_key['correct']) && !empty($question->answer_key['correct']))
                                            <ul class="list-disc list-inside">
                                                @foreach($question->answer_key['correct'] as $optionIndex)
                                                    @if(is_numeric($optionIndex) && isset($question->options[$optionIndex]))
                                                        <li class="text-green-600">
                                                            {{ chr(65 + $optionIndex) }}. {{ $question->options[$optionIndex] }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="text-sm text-gray-500 mt-3 pt-3 border-t border-gray-200">
                            Points: {{ $answer ? $answer->score_awarded : 0 }} / {{ $question->score }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                <a href="{{ route('courses.index') }}" 
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                    Back to Courses
                </a>
            </div>
        </div>
    </div>
</div>
