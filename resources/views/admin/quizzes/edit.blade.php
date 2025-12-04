@extends('admin.layout')

@section('title', 'Edit Kuis - ' . $quiz->title)

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Kuis</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Perbarui pengaturan dan informasi kuis</p>
            </div>
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

    <!-- Quiz Info Header -->
    <div
        class="mb-6 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-300">
                    <span class="inline-flex items-center">
                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        {{ $quiz->category->name }}
                    </span>
                    <span class="inline-flex items-center">
                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $quiz->questions->count() }} soal
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Harap perbaiki kesalahan berikut:
                    </h3>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}"
            class="divide-y divide-gray-200 dark:divide-gray-700">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Informasi Dasar</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui detail kuis</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Quiz Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Kuis
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}"
                                required placeholder="Masukkan judul kuis yang menarik..."
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kategori
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('category_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $quiz->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi
                        <span class="text-gray-400 text-xs font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="3"
                            placeholder="Tambahkan deskripsi singkat untuk membantu pemain memahami tentang kuis ini..."
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm resize-none @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $quiz->description) }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Timing Settings Section -->
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Pengaturan Waktu</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui batas waktu untuk kuis</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Total Timeout -->
                    <div>
                        <label for="timeout_seconds"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Total Batas Waktu
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="timeout_seconds" name="timeout_seconds"
                                value="{{ old('timeout_seconds', $quiz->timeout_seconds) }}" min="30"
                                max="3600" required placeholder="60"
                                class="block w-full px-3 py-2 pr-16 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('timeout_seconds') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 text-sm">detik</span>
                            </div>
                        </div>
                        @error('timeout_seconds')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Rentang waktu (30-300 detik)
                        </p>
                    </div>

                    <!-- Per Question Time -->
                    <div>
                        <label for="per_question_time"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Waktu Per Soal
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="per_question_time" name="per_question_time"
                                value="{{ old('per_question_time', $quiz->per_question_time) }}" min="5"
                                max="300" required placeholder="15"
                                class="block w-full px-3 py-2 pr-16 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('per_question_time') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 text-sm">detik</span>
                            </div>
                        </div>
                        @error('per_question_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Rentang waktu (5-60 detik)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-between space-x-3 rounded-b-lg">
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Perubahan akan disimpan secara otomatis
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        Perbarui Kuis
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
