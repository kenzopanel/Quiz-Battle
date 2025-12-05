@extends('admin.layout')

@section('title', 'Detail Soal')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Soal</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pertanyaan</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $question->question_text }}</dd>
                    </div>
                    @if ($question->image_path)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gambar</dt>
                            <dd class="mt-1">
                                <img src="{{ $question->image_url }}" alt="Question image"
                                    class="w-full h-48 object-cover rounded-lg">
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $question->created_at->format('F j, Y \a\t g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Opsi Jawaban</h3>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($question->options as $index => $option)
                        <li class="px-4 py-4 {{ $option->is_correct ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center h-8 w-8 rounded-full {{ $option->is_correct ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }} text-sm font-medium mr-3">
                                    {{ chr(65 + $index) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium {{ $option->is_correct ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-white' }}">
                                        {{ $option->option_text }}
                                    </p>
                                    @if ($option->is_correct)
                                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">✓ Jawaban Benar</p>
                                    @endif
                                </div>
                                @if ($option->is_correct)
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="flex justify-between">
            <div class="flex space-x-3">
                <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Edit Soal
                </a>
            </div>

            <form method="POST" action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium cursor-pointer">
                    Hapus Soal
                </button>
            </form>
        </div>
    </div>
@endsection
