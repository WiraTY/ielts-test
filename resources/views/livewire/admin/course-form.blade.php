<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Create New Course</h1>
                    
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                                <input type="text" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course title">
                            </div>
                            
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                                <input type="text" id="slug" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course slug">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course description"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
                                <input type="file" id="thumbnail" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center mb-6">
                            <input id="is_trial" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_trial" class="ml-2 block text-sm text-gray-700">
                                This is a trial course
                            </label>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Save Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
