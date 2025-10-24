<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Create New Lesson</h1>
                    
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Lesson Title</label>
                                <input type="text" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson title">
                            </div>
                            
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                                <input type="text" id="slug" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson slug">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea id="content" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson content"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                                <input type="text" id="video_url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter video URL">
                            </div>
                            
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (seconds)</label>
                                <input type="number" id="duration" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in seconds">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                                <input type="number" id="order" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson order">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Save Lesson
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
