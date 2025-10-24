@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Levels', 'url' => route('admin.levels.index')]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Level Management</h1>
                    <a href="{{ route('admin.levels.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                        Add New Level
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Display Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($levels as $level)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $level->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $level->display_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $level->order }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($level->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.levels.edit', $level) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.levels.show', $level) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        <form action="{{ route('admin.levels.destroy', $level) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this level? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No levels found. <a href="{{ route('admin.levels.create') }}" class="text-indigo-600 hover:text-indigo-900">Create the first level.</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Level Management Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Level Management Guide</h3>
                    <p class="text-blue-700 mb-3">Levels define the progression path for students in your educational platform:</p>
                    <ul class="text-blue-700 list-disc pl-5 space-y-1">
                        <li><strong>Name</strong>: Internal identifier for the level (must be unique)</li>
                        <li><strong>Display Name</strong>: User-friendly name shown to students</li>
                        <li><strong>Order</strong>: Sequence in which students progress through levels</li>
                        <li><strong>Status</strong>: Only active levels can be assigned to students</li>
                    </ul>
                    <p class="mt-3 text-sm text-blue-600">Note: Levels used in courses or assigned to users cannot be deleted.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection