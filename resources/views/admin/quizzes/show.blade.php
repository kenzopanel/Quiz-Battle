@extends('admin.layout')

@section('title', $quiz->title)

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <!-- Quiz Header Card -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-8 sm:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ $quiz->title }}</h1>
                                <p class="text-indigo-100 mt-1">{{ $quiz->category->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-6 text-sm text-indigo-100">
                            <div class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span class="ml-1">{{ $quiz->category->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $quiz->questions->count() }} soal
                            </div>
                            <div class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $quiz->timeout_seconds }} detik
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                            class="inline-flex items-center px-4 py-2 border border-white/20 rounded-lg text-sm font-medium text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Kuis
                        </a>
                        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-indigo-600 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Kelola Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Quiz Details Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Detail Kuis</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        <svg class="-ml-0.5 mr-1.5 h-3 w-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ $quiz->category->name }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Total Soal
                                </label>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $quiz->questions->count() }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Tanggal Dibuat
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $quiz->created_at->format('F j, Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Total Batas Waktu
                                </label>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $quiz->timeout_seconds }} detik</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Batas Waktu Tiap Soal
                                </label>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $quiz->per_question_time }} detik</div>
                            </div>
                        </div>

                        @if ($quiz->description)
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Deskripsi</label>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $quiz->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Questions Section -->
                @if ($quiz->questions->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Soal</h3>
                                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800">
                                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Soal
                                </a>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($quiz->questions as $index => $question)
                                <div
                                    class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <span
                                                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm font-medium">
                                                    {{ $index + 1 }}
                                                </span>
                                                <h4 class="text-base font-medium text-gray-900 dark:text-white">
                                                    {{ $question->question_text }}
                                                </h4>
                                            </div>

                                            <div class="ml-9 grid grid-cols-1 md:grid-cols-2 gap-2">
                                                @foreach ($question->options as $optionIndex => $option)
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-xs font-medium {{ $option->is_correct ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                                            {{ chr(65 + $optionIndex) }}
                                                        </span>
                                                        <span
                                                            class="text-sm {{ $option->is_correct ? 'text-green-700 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                                                            {{ $option->option_text }}
                                                        </span>
                                                        @if ($option->is_correct)
                                                            <svg class="h-4 w-4 text-green-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2 ml-4">
                                            <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                                title="Edit question">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this question?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600"
                                                    title="Delete question">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Empty State for Questions -->
                    <div
                        class="text-center py-12 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 48 48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14c0 4.418-7.163 8-16 8-1.381 0-2.721-.087-4-.252" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada soal</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Buat soal untuk kuis ini.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Buat Soal
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Kuis
                        </a>

                        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out">
                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kelola Soal
                        </a>

                        @if ($quiz->questions->count() > 0)
                            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Soal
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistik Kuis</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Soal</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quiz->questions->count() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Durasi Perkiraan</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $quiz->questions->count() * $quiz->per_question_time }} detik
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->questions->count() > 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                {{ $quiz->questions->count() > 0 ? 'Siap' : 'Belum ada soal' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quiz->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini? Ini juga akan menghapus semua soal dan tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 dark:border-red-700 rounded-lg text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/75 transition duration-150 ease-in-out cursor-pointer">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Kuis
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
