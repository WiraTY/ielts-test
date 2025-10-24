<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                <div class="text-lg font-semibold text-red-600" id="timer">
                    Time Left: <span wire:poll.1s>{{ gmdate('H:i:s', $timeLeft) }}</span>
                </div>
            </div>
            
            @if(!$isSubmitted)
                @php
                    $currentQuestion = $questions[$current] ?? null;
                @endphp
                
                @if($currentQuestion)
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-medium text-gray-800">
                                Question {{ $current + 1 }} of {{ $questions->count() }}
                            </h2>
                            <span class="text-sm font-medium text-gray-500">
                                {{ $currentQuestion->score }} point(s)
                            </span>
                        </div>
                        
                        <div class="text-lg mb-6">{!! $currentQuestion->question_text !!}</div>
                        
                        <div class="space-y-3">
                            @if($currentQuestion->type === 'mcq')
                                @foreach($currentQuestion->options as $index => $option)
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               id="option_{{ $current }}_{{ $index }}" 
                                               name="answer_{{ $current }}"
                                               value="{{ $index }}"
                                               wire:model="answers.{{ $current }}.option"
                                               class="h-4 w-4 text-blue-600">
                                        <label for="option_{{ $current }}_{{ $index }}" class="ml-3 text-gray-700">
                                            {{ chr(65 + $index) }}. {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif($currentQuestion->type === 'multi')
                                @foreach($currentQuestion->options as $index => $option)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="option_{{ $current }}_{{ $index }}" 
                                               value="{{ $index }}"
                                               wire:model="answers.{{ $current }}.options"
                                               class="h-4 w-4 text-blue-600">
                                        <label for="option_{{ $current }}_{{ $index }}" class="ml-3 text-gray-700">
                                            {{ chr(65 + $index) }}. {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif($currentQuestion->type === 'essay')
                                <textarea 
                                    wire:model="answers.{{ $current }}.text"
                                    rows="4"
                                    class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Write your answer here..."></textarea>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button 
                            wire:click="prevQuestion"
                            @if($current === 0) disabled @endif
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded disabled:opacity-50">
                            Previous
                        </button>
                        
                        @if($current < $questions->count() - 1)
                            <button 
                                wire:click="nextQuestion"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Next
                            </button>
                        @else
                            <button 
                                wire:click="submit"
                                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                Submit Quiz
                            </button>
                        @endif
                    </div>
                @else
                    <p>No questions available for this quiz.</p>
                @endif
            @else
                <div class="text-center py-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Quiz Completed!</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Your score: <span class="font-bold text-xl">{{ $attempt->score }}</span>
                    </p>
                    <a href="{{ route('quizzes.result', $attempt->id) }}" 
                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        View Results
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
