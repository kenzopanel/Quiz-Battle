@extends('admin.layout')

@section('title', 'Soal - ' . $quiz->title)

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Soal</h1>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.quizzes.show', $quiz) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                </div>
                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Tambah Soal
                </a>
            </div>
        </div>
    </div>

    @if ($quiz->questions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($quiz->questions as $index => $question)
                    <li>
                        <div class="px-4 py-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center">
                                        <span
                                            class="flex-shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm font-medium mr-3">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ $question->question_text }}
                                            </h4>
                                            @if ($question->image_path)
                                                <img src="{{ $question->image_url }}" alt="Question image"
                                                    class="w-24 h-24 object-cover rounded mt-2">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3 ml-11 space-y-2">
                                        @foreach ($question->options as $optionIndex => $option)
                                            <div class="flex items-center">
                                                @if ($option->is_correct)
                                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span
                                                        class="text-green-700 dark:text-green-300 font-medium">{{ $option->option_text }}</span>
                                                @else
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full mr-2">
                                                    </div>
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400">{{ $option->option_text }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('admin.quizzes.questions.show', [$quiz, $question]) }}"
                                        class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Lihat</a>
                                    <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}"
                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-200 dark:hover:text-gray-400 text-sm font-medium">Edit</a>
                                    <form method="POST"
                                        action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-700 text-sm font-medium cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <svg class="mx-auto w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada soal</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan soal pertama ke kuis ini.</p>
            <div class="mt-6">
                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Tambah Soal
                </a>
            </div>
        </div>
    @endif
@endsection
