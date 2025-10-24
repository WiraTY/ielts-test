<div>
    @if(!$isSubmitted)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Progress bar -->
            <div class="h-2 bg-gray-200">
                <div class="h-full bg-blue-500" 
                     style="width: {{ (($currentQuestionIndex + 1) / count($questions)) * 100 }}%"></div>
            </div>
            
            <!-- Question navigation -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}
                    </span>
                    @if($timeLeft)
                        <span class="text-sm font-medium text-red-600">
                            Time left: {{ floor($timeLeft / 60) }}:{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Current question -->
            @if(isset($questions[$currentQuestionIndex]))
                @php
                    $question = $questions[$currentQuestionIndex];
                @endphp
                
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">
                        {{ $question->question_text }}
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach(['A', 'B', 'C', 'D'] as $option)
                            <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50 
                                   {{ $selectedAnswers[$currentQuestionIndex] === $option ? 'bg-blue-50 border-blue-500' : 'border-gray-300' }}">
                                <input type="radio" 
                                       name="answer_{{ $currentQuestionIndex }}" 
                                       value="{{ $option }}" 
                                       wire:click="selectAnswer({{ $currentQuestionIndex }}, '{{ $option }}')"
                                       {{ $selectedAnswers[$currentQuestionIndex] === $option ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700">
                                    {{ $option }}. {{ $question->options[$option] ?? '' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex justify-between">
                        <button wire:click="previousQuestion" 
                                @if($currentQuestionIndex === 0) disabled @endif
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        
                        @if($currentQuestionIndex < count($questions) - 1)
                            <button wire:click="nextQuestion" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Next
                            </button>
                        @else
                            <button wire:click="submitTest" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                Submit Test
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Question navigator -->
        <div class="mt-4 bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Questions</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($questions as $index => $question)
                    <button wire:click="goToQuestion({{ $index }})"
                            class="w-8 h-8 rounded-full text-sm {{ $currentQuestionIndex === $index ? 'bg-blue-500 text-white' : ($selectedAnswers[$index] ? 'bg-green-500 text-white' : 'bg-white border border-gray-300 text-gray-700') }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Test Submitted</h3>
            <p class="mt-1 text-sm text-gray-500">Your placement test has been submitted successfully.</p>
            <div class="mt-6">
                <a href="{{ route('placement-tests.result', $attempt) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Results
                </a>
            </div>
        </div>
    @endif
    
    <!-- Timer script -->
    @if($timeLeft && !$isSubmitted)
    <script>
        document.addEventListener('livewire:initialized', function () {
            let timerInterval = setInterval(function() {
                @this.call('updateTime');
            }, 1000);
            
            // Clear interval when component is destroyed
            Livewire.on('destroy', () => {
                clearInterval(timerInterval);
            });
        });
    </script>
    @endif
</div>