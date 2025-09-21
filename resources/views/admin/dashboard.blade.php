@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                    <a href="{{ route('admin.courses.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Create New Course
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-100 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-blue-800">Total Users</h3>
                        <p class="text-3xl font-bold text-blue-600">2</p>
                    </div>
                    
                    <div class="bg-green-100 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-green-800">Total Courses</h3>
                        <p class="text-3xl font-bold text-green-600">{{ \App\Models\Course::count() }}</p>
                    </div>
                    
                    <div class="bg-yellow-100 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-yellow-800">Total Lessons</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ \App\Models\Lesson::count() }}</p>
                    </div>
                    
                    <div class="bg-purple-100 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-purple-800">Total Quizzes</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Quiz::count() }}</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Course Management</h2>
                        <a href="{{ route('admin.courses.create') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                            Create New Course
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Course Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lessons
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(\App\Models\Course::with('lessons')->get() as $course)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($course->published_at)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Published
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $course->lessons->count() }} lessons
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-900">Manage</a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No courses found. <a href="{{ route('admin.courses.create') }}" class="text-blue-500 hover:text-blue-700">Create your first course</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- User Management -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">User Management</h2>
                        <p class="text-gray-600 mb-4">Manage all users in the system</p>
                        <a href="{{ route('admin.users.index') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                            Manage Users
                        </a>
                    </div>
                    
                    <!-- Reports & Analytics -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">Reports & Analytics</h2>
                        <p class="text-gray-600 mb-4">View reports and analytics</p>
                        <a href="{{ route('admin.reports.index') }}" class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded text-center">
                            View Reports
                        </a>
                    </div>
                    
                    <!-- User Level Tracking -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">User Level Tracking</h2>
                        <p class="text-gray-600 mb-4">Track user levels and progress</p>
                        <a href="{{ route('admin.user-level-tracking') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded text-center">
                            Track Levels
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection