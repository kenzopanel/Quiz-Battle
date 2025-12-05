@extends('admin.layout')

@section('title', 'Kategori')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Tambah Kategori
        </a>
    </div>

    @php
        $divider = $categories->count() > 0 ? 'divide-y divide-gray-200 dark:divide-gray-700' : '';
    @endphp

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        @if ($categories->count() > 0)
            <ul class="{{ $divider }}">
                @foreach ($categories as $category)
                    <li>
                        <div class="px-4 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </h3>
                                        <span
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ $category->quizzes_count }} kuis
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Dibuat {{ $category->created_at->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                    class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="text-gray-200 hover:text-gray-400 text-sm font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-700 text-sm font-medium cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if ($categories->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 30a9.971 9.971 0 019.287 6.286M30 20a6 6 0 11-12 0 6 6 0 0112 0zm12 25a6 6 0 11-12 0 6 6 0 0112 0zm-28 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada kategori</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat kategori baru.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Tambah Kategori
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
