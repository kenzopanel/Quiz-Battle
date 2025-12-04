@extends('admin.layout')

@section('title', $category->name)

@section('content')
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Detail Kategori</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Informasi tentang kategori kuis ini.
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $category->name }}</dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $category->created_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kuis</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $category->quizzes->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if ($category->quizzes->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Kuis dalam kategori ini</h3>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($category->quizzes as $quiz)
                    <li>
                        <div class="px-4 py-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                    {{ $quiz->title }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-3 dark:text-gray-400">
                                    Code: <span
                                        class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $quiz->code }}</span>
                                    • {{ $quiz->questions_count }} {{ $quiz->questions_count }} pertanyaan
                                    • Dibuat {{ $quiz->created_at->format('M j, Y') }}
                                </p>
                                @if ($quiz->description)
                                    <p class="text-sm text-gray-600 mt-2 dark:text-gray-300 mt-1">{{ $quiz->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}"
                                    class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                                    class="text-gray-200 hover:text-gray-400 text-sm font-medium">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2m-2 0V3a1 1 0 011-1h4a1 1 0 011 1v2m-2 0h2m-4 0h-4m0 0a2 2 0 00-2 2v10a2 2 0 002 2h4a2 2 0 002-2V7a2 2 0 00-2-2h-4z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada kuis</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kategori ini belum memiliki kuis.</p>
            <div class="mt-6">
                <a href="{{ route('admin.quizzes.create') }}?category={{ $category->id }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Buat Kuis
                </a>
            </div>
        </div>
    @endif

    <div class="mt-6 flex justify-between">
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Edit Kategori
            </a>
        </div>

        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium cursor-pointer"
                {{ $category->quizzes->count() > 0 ? 'disabled' : '' }}>
                Hapus Kategori
            </button>
        </form>
    </div>
@endsection
