@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Reports', 'url' => route('admin.reports.index')],
        ['label' => 'Level Progression Tracking', 'url' => null]
    ]" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Level Progression Tracking</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.reports.level-progression') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                <select id="level" name="level" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">All Levels</option>
                    <option value="starter" {{ request('level') == 'starter' ? 'selected' : '' }}>Starter</option>
                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="elementary" {{ request('level') == 'elementary' ? 'selected' : '' }}>Elementary</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">All Statuses</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="User name">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-800">Total Users</div>
            <div class="mt-1 text-2xl font-semibold text-blue-900">{{ $totalUsers }}</div>
        </div>
        
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <div class="text-sm font-medium text-green-800">In Progress</div>
            <div class="mt-1 text-2xl font-semibold text-green-900">{{ $inProgressCount }}</div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
            <div class="text-sm font-medium text-yellow-800">Completed</div>
            <div class="mt-1 text-2xl font-semibold text-yellow-900">{{ $completedCount }}</div>
        </div>
        
        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-800">Avg Completion</div>
            <div class="mt-1 text-2xl font-semibold text-purple-900">{{ number_format($averageCompletion, 2) }}%</div>
        </div>
        
        <div class="bg-pink-50 border border-pink-100 rounded-lg p-4">
            <div class="text-sm font-medium text-pink-800">Avg Time</div>
            <div class="mt-1 text-2xl font-semibold text-pink-900">{{ $averageTimeToComplete }}</div>
        </div>
    </div>

    <!-- Progression Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Level Progression Details</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Tracking user progress through different levels.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Level</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses Completed</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($progressions as $progression)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $progression->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $progression->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($progression->user->current_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progression->completion_percentage }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">{{ number_format($progression->completion_percentage, 2) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $progression->completed_courses }}/{{ $progression->total_courses }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($progression->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            In Progress
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $progression->started_at ? $progression->started_at->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $progression->completed_at ? $progression->completed_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No progression data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($progressions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $progressions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection