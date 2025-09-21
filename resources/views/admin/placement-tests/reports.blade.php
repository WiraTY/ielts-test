@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Placement Tests', 'url' => route('admin.placement-tests.index')],
        ['label' => 'Reports', 'url' => null]
    ]" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Placement Test Reports</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.placement-tests.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Tests
            </a>
        </div>
    </div>

    <!-- Reports Content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Test Performance Overview</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed analytics and reports for placement tests.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <!-- Filters -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('admin.placement-tests.reports') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="placement_test_id" class="block text-sm font-medium text-gray-700">Placement Test</label>
                            <select id="placement_test_id" name="placement_test_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">All Tests</option>
                                @foreach($placementTests as $test)
                                    <option value="{{ $test->id }}" {{ request('placement_test_id') == $test->id ? 'selected' : '' }}>
                                        {{ $test->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        
                        <div class="md:col-span-3 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-blue-800">Total Attempts</div>
                        <div class="mt-1 text-2xl font-semibold text-blue-900">{{ $totalAttempts }}</div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-green-800">Average Score</div>
                        <div class="mt-1 text-2xl font-semibold text-green-900">{{ number_format($averageScore, 2) }}%</div>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-yellow-800">Highest Score</div>
                        <div class="mt-1 text-2xl font-semibold text-yellow-900">{{ $highestScore }}%</div>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-purple-800">Lowest Score</div>
                        <div class="mt-1 text-2xl font-semibold text-purple-900">{{ $lowestScore }}%</div>
                    </div>
                </div>

                <!-- Level Distribution -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Level Distribution</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <div class="text-sm font-medium text-blue-800">Starter</div>
                            <div class="mt-1 text-2xl font-semibold text-blue-900">{{ $starterCount }}</div>
                        </div>
                        
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <div class="text-sm font-medium text-green-800">Beginner</div>
                            <div class="mt-1 text-2xl font-semibold text-green-900">{{ $beginnerCount }}</div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                            <div class="text-sm font-medium text-yellow-800">Elementary</div>
                            <div class="mt-1 text-2xl font-semibold text-yellow-900">{{ $elementaryCount }}</div>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                            <div class="text-sm font-medium text-purple-800">Intermediate</div>
                            <div class="mt-1 text-2xl font-semibold text-purple-900">{{ $intermediateCount }}</div>
                        </div>
                        
                        <div class="bg-pink-50 border border-pink-100 rounded-lg p-4">
                            <div class="text-sm font-medium text-pink-800">Advanced</div>
                            <div class="mt-1 text-2xl font-semibold text-pink-900">{{ $advancedCount }}</div>
                        </div>
                    </div>
                </div>

                <!-- Score Distribution Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Score Distribution</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-700">0-20%</div>
                                <div class="mt-1 text-lg font-semibold text-blue-600">{{ $scoreRange0_20 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalAttempts > 0 ? ($scoreRange0_20 / $totalAttempts) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-700">21-40%</div>
                                <div class="mt-1 text-lg font-semibold text-green-600">{{ $scoreRange21_40 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalAttempts > 0 ? ($scoreRange21_40 / $totalAttempts) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-700">41-60%</div>
                                <div class="mt-1 text-lg font-semibold text-yellow-600">{{ $scoreRange41_60 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $totalAttempts > 0 ? ($scoreRange41_60 / $totalAttempts) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-700">61-80%</div>
                                <div class="mt-1 text-lg font-semibold text-purple-600">{{ $scoreRange61_80 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $totalAttempts > 0 ? ($scoreRange61_80 / $totalAttempts) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-700">81-100%</div>
                                <div class="mt-1 text-lg font-semibold text-pink-600">{{ $scoreRange81_100 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-pink-600 h-2 rounded-full" style="width: {{ $totalAttempts > 0 ? ($scoreRange81_100 / $totalAttempts) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attempts Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Level</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attempts as $attempt)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $attempt->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attempt->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attempt->placementTest->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $attempt->score }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attempt->assigned_level)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($attempt->assigned_level) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attempt->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attempt->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($attempt->status === 'in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($attempt->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No test attempts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attempts->hasPages())
                    <div class="mt-6">
                        {{ $attempts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection