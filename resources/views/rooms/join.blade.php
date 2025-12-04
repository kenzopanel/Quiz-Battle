@extends('layouts.app')

@section('title', 'Gabung Pakai Kode')

@section('content')
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="text-center mb-8">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                        </svg>
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gabung Pakai Kode</h1>
                <p class="text-gray-600 dark:text-gray-300">Masukkan kode ruang untuk bergabung ke pertarungan</p>
            </div>

            <form action="{{ route('rooms.join.request') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kode Ruang
                    </label>
                    <input type="text" id="code" name="code" maxlength="8" placeholder="ABC12345"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-center text-lg font-mono tracking-wider uppercase @error('code') border-red-500 @enderror"
                        value="{{ old('code') }}" required autocomplete="off">
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 cursor-pointer">
                    Gabung
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Tidak punya kode?
                    </p>
                    <a href="{{ route('rooms.index') }}"
                        class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                        ← Ruang Publik
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const codeInput = document.getElementById('code');

                // Auto-uppercase and limit to 8 characters
                codeInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.toUpperCase().slice(0, 8);
                });

                // Auto-focus on load
                codeInput.focus();
            });
        </script>
    @endpush
@endsection
