<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-100 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-blue-800">Total Users</h3>
                            <p class="text-3xl font-bold text-blue-600">0</p>
                        </div>
                        
                        <div class="bg-green-100 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-green-800">Total Courses</h3>
                            <p class="text-3xl font-bold text-green-600">0</p>
                        </div>
                        
                        <div class="bg-yellow-100 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-yellow-800">Total Lessons</h3>
                            <p class="text-3xl font-bold text-yellow-600">0</p>
                        </div>
                        
                        <div class="bg-purple-100 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-purple-800">Total Quizzes</h3>
                            <p class="text-3xl font-bold text-purple-600">0</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                            <div class="space-y-3">
                                <a href="{{ route('admin.courses.create') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                                    Create New Course
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded text-center">
                                    Manage Users
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded text-center">
                                    View Reports
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
