@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Placement Tests', 'url' => route('admin.placement-tests.index')],
                    ['label' => $placementTest->title, 'url' => route('admin.placement-tests.edit', $placementTest)],
                    ['label' => 'Question Details', 'url' => route('admin.placement-tests.questions.show', [$placementTest, $question])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Question Details</h1>
                    <div>
                        <a href="{{ route('admin.placement-tests.questions.edit', [$placementTest, $question]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Edit
                        </a>
                        <a href="{{ route('admin.placement-tests.edit', $placementTest) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Placement Test
                        </a>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Question Text</h3>
                    <p class="text-gray-700">{{ $question->question_text }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Options</h3>
                        <ul class="list-disc pl-5">
                            <li class="{{ $question->correct_answer === 'A' ? 'font-bold text-green-600' : '' }}">
                                A. {{ $question->options['A'] ?? '' }}
                                @if($question->correct_answer === 'A')
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Correct
                                    </span>
                                @endif
                            </li>
                            <li class="{{ $question->correct_answer === 'B' ? 'font-bold text-green-600' : '' }}">
                                B. {{ $question->options['B'] ?? '' }}
                                @if($question->correct_answer === 'B')
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Correct
                                    </span>
                                @endif
                            </li>
                            <li class="{{ $question->correct_answer === 'C' ? 'font-bold text-green-600' : '' }}">
                                C. {{ $question->options['C'] ?? '' }}
                                @if($question->correct_answer === 'C')
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Correct
                                    </span>
                                @endif
                            </li>
                            <li class="{{ $question->correct_answer === 'D' ? 'font-bold text-green-600' : '' }}">
                                D. {{ $question->options['D'] ?? '' }}
                                @if($question->correct_answer === 'D')
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Correct
                                    </span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Score</h3>
                            <p class="text-gray-700">{{ $question->score }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order</h3>
                            <p class="text-gray-700">{{ $question->order }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Correct Answer</h3>
                            <p class="text-gray-700">
                                <span class="font-bold text-green-600">{{ $question->correct_answer }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Answers Section -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h2 class="text-xl font-bold mb-4">Student Answers</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Student
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Selected Answer
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Correct
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Score Awarded
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($question->answers as $answer)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $answer->attempt->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $answer->attempt->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $answer->selected_answer }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($answer->is_correct)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Yes
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    No
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $answer->score_awarded }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $answer->created_at->format('M d, Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No answers found for this question.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection