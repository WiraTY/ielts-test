@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'User Level Tracking', 'url' => null]
    ]" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">User Level Tracking</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.user-level-tracking') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <label for="has_taken_test" class="block text-sm font-medium text-gray-700">Placement Test Status</label>
                <select id="has_taken_test" name="has_taken_test" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">All Users</option>
                    <option value="1" {{ request('has_taken_test') == '1' ? 'selected' : '' }}>Taken Test</option>
                    <option value="0" {{ request('has_taken_test') == '0' ? 'selected' : '' }}>Not Taken Test</option>
                </select>
            </div>
            
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Name or email">
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
            <div class="text-sm font-medium text-green-800">Taken Placement Test</div>
            <div class="mt-1 text-2xl font-semibold text-green-900">{{ $usersWithTest }}</div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
            <div class="text-sm font-medium text-yellow-800">Starter Level</div>
            <div class="mt-1 text-2xl font-semibold text-yellow-900">{{ $starterLevelCount }}</div>
        </div>
        
        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-800">Beginner Level</div>
            <div class="mt-1 text-2xl font-semibold text-purple-900">{{ $beginnerLevelCount }}</div>
        </div>
        
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
            <div class="text-sm font-medium text-indigo-800">Elementary Level</div>
            <div class="mt-1 text-2xl font-semibold text-indigo-900">{{ $elementaryLevelCount }}</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-pink-50 border border-pink-100 rounded-lg p-4">
            <div class="text-sm font-medium text-pink-800">Intermediate Level</div>
            <div class="mt-1 text-2xl font-semibold text-pink-900">{{ $intermediateLevelCount }}</div>
        </div>
        
        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
            <div class="text-sm font-medium text-red-800">Advanced Level</div>
            <div class="mt-1 text-2xl font-semibold text-red-900">{{ $advancedLevelCount }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Level Details</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">List of users with their assigned levels and progress.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Level</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Level</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unlocked Levels</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placement Test</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->assigned_level)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($user->assigned_level) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->current_level)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ ucfirst($user->current_level) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->unlocked_levels && is_array($user->unlocked_levels))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->unlocked_levels as $level)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ ucfirst($level) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->has_taken_placement_test)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Taken
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Not Taken
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection