<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Create New Quiz</h1>
                    
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
                                <input type="text" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quiz title">
                            </div>
                            
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                                <input type="number" id="duration" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in minutes">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="pass_score" class="block text-sm font-medium text-gray-700 mb-2">Pass Score (%)</label>
                                <input type="number" id="pass_score" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter pass score" min="0" max="100">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h2 class="text-xl font-bold mb-4">Questions</h2>
                            
                            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-lg font-medium">Question 1</h3>
                                    <button type="button" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="question_text_1" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                                    <textarea id="question_text_1" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question text"></textarea>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="mcq">Multiple Choice (Single Answer)</option>
                                            <option value="multi">Multiple Choice (Multiple Answers)</option>
                                            <option value="essay">Essay</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="score_1" class="block text-sm font-medium text-gray-700 mb-2">Score</label>
                                        <input type="number" id="score_1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter score" min="0">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input type="radio" id="correct_option_1_0" name="correct_option_1" class="h-4 w-4 text-blue-600">
                                            <input type="text" class="ml-2 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 1">
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="correct_option_1_1" name="correct_option_1" class="h-4 w-4 text-blue-600">
                                            <input type="text" class="ml-2 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 2">
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="correct_option_1_2" name="correct_option_1" class="h-4 w-4 text-blue-600">
                                            <input type="text" class="ml-2 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 3">
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="correct_option_1_3" name="correct_option_1" class="h-4 w-4 text-blue-600">
                                            <input type="text" class="ml-2 flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Question
                            </button>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Save Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
