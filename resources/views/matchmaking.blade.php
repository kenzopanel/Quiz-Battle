@extends('layouts.app')

@section('title', 'Mencari Lawan')

@section('content')
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <!-- Spinner Animation -->
            <div class="relative mb-8">
                <div class="relative w-24 h-24 mx-auto">
                    <div class="w-full h-full border-4 border-blue-200 dark:border-blue-800 rounded-full animate-pulse">
                    </div>
                    <div
                        class="absolute inset-0 w-full h-full border-4 border-transparent border-t-blue-600 rounded-full animate-spin">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Mencari Lawan</h1>

                <!-- Countdown -->
                <div class="mt-2 w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                    <div id="progress" class="bg-blue-600 h-2 rounded-full transition-all duration-1000"
                        style="width: 100%"></div>
                </div>
            </div>

            <!-- Cancel Button -->
            <div class="space-y-4">
                <form action="{{ route('matchmaking.cancel') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                        Batalkan
                    </button>
                </form>

                <a href="{{ route('index') }}"
                    class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                    ← Kembali
                </a>
            </div>

            <!-- Match Status -->
            <div id="match-status" class="mt-8 hidden">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="text-green-400 text-xl mr-3"></span>
                        <div class="text-left">
                            <p class="font-semibold text-green-800 dark:text-green-200">Lawan ditemukan</p>
                            <p class="text-sm text-green-700 dark:text-green-300">Menuju pertarungan...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeout Status -->
            <div id="timeout-status" class="mt-8 hidden">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="text-left">
                            <p class="font-semibold text-red-800 dark:text-red-200">Tidak menemukan lawan</p>
                            <p class="text-sm text-red-700 dark:text-red-300">Tidak ada lawan ditemukan. Coba lagi?</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <form action="{{ route('matchmaking.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                            Coba Lagi
                        </button>
                    </form>

                    <a href="{{ route('index') }}"
                        class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-colors duration-200">
                        Ganti Quiz Lain
                    </a>
                </div>
            </div>

            <!-- Tips while waiting -->
            <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <h3 class="font-medium text-gray-900 dark:text-white mb-2">Tips</h3>
                <ul class="text-sm list-disc text-gray-600 dark:text-gray-400 space-y-1 text-left px-4">
                    <li>Pastikan koneksi internet kamu stabil</li>
                    <li>Jangan refresh atau tutup tab ini saat pertarungan</li>
                    <li>Jawab dengan cepat tetapi akurat untuk menang</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let timeRemaining = 30;
                let countdownInterval;
                let matched = false;

                const countdownEl = document.getElementById('countdown');
                const progressEl = document.getElementById('progress');
                const matchStatusEl = document.getElementById('match-status');
                const timeoutStatusEl = document.getElementById('timeout-status');

                // Setup Echo listener for match found
                if (window.Echo && window.sessionToken) {
                    window.Echo.channel(`player.${window.sessionToken}`)
                        .listen('.match.found', (e) => {
                            console.log('Match found!', e);
                            matched = true;

                            if (countdownInterval) {
                                clearInterval(countdownInterval);
                            }

                            // Show match found status
                            matchStatusEl.classList.remove('hidden');

                            // Redirect to battle
                            setTimeout(() => {
                                window.location.href = `/battle/${e.battle_id}`;
                            }, 2000);
                        });
                }

                // Countdown timer
                countdownInterval = setInterval(() => {
                    timeRemaining--;

                    // Update progress bar
                    const progressPercent = (timeRemaining / 30) * 100;
                    progressEl.style.width = `${progressPercent}%`;

                    // Change color as time runs out
                    if (timeRemaining <= 10) {
                        progressEl.classList.remove('bg-blue-600');
                        progressEl.classList.add('bg-red-600');
                    }

                    if (timeRemaining <= 0) {
                        clearInterval(countdownInterval);

                        if (!matched) {
                            // Show timeout status
                            timeoutStatusEl.classList.remove('hidden');
                        }
                    }
                }, 1000);

                // Cleanup on page unload
                window.addEventListener('beforeunload', function() {
                    if (countdownInterval) {
                        clearInterval(countdownInterval);
                    }
                });
            });
        </script>
    @endpush
@endsection
