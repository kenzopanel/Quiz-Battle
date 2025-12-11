@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ config('app.name') }}
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                Volume dan Luas Permukaan Bangun Ruang
            </p>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-8">
                Pilih kategori dan tantang lawan dalam pertarungan kuis!
            </p>

            <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    Pertarungan 1v1
                </div>
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Anti Curang
                </div>
            </div>
        </div>

        <!-- Room Options -->
        <div class="mb-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('rooms.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                Ruang Publik
            </a>
            <a href="{{ route('rooms.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                Buat Ruang
            </a>
            <a href="{{ route('rooms.join') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                Gabung Pakai Kode
            </a>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categories as $category)
                <div class="group">
                    <form action="{{ route('matchmaking.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">

                        <button type="submit"
                            class="w-full p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 group-hover:scale-105 cursor-pointer">
                            <div class="text-center">
                                <!-- Category Name -->
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $category->name }}
                                </h3>

                                <!-- Quiz Count -->
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $category->quizzes_count }} Kuis
                                </p>
                            </div>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        @if ($categories->isEmpty())
            <div class="text-center py-12">
                <div
                    class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <span class="text-2xl"></span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Kategori</h3>
                <p class="text-gray-500 dark:text-gray-400">Kategori akan muncul di sini setelah dibuat.</p>
            </div>
        @endif

        <!-- How to Play Section -->
        <div class="mt-16 bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Cara Bermain</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl">🎯</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">1. Pilih Kategori</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pilih kategori kuis yang kamu inginkan</p>
                </div>

                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl">⚔️</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">2. Cari Lawan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tunggu hingga menemukan lawan untukmu</p>
                </div>

                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl">🏆</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">3. Bertarung!</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Jawab pertanyaan dengan cepat dan benar untuk
                        memenangkan pertarungan</p>
                </div>
            </div>

            <div
                class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-700 dark:text-yellow-400 text-center">
                    <strong>Anti curang:</strong> Menutup, pindah tab, atau keluar akan mengakibatkan
                    kekalahan otomatis
                </p>
            </div>
        </div>
    </div>

    <!-- Static Modal -->
    <div id="modal"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 px-3 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 w-full max-w-2xl max-h-full">
            <div class="flex items-center justify-between border-b border-gray-300 pb-4 md:pb-5 dark:border-gray-700">
                <h3 class="text-lg font-medium text-heading dark:text-white">
                    Tujuan Aplikasi Ini
                </h3>
                <button type="button"
                    class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center dark:text-gray-400 cursor-pointer closeModal">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="py-4 px-5 text-base/7 text-gray-700 dark:text-gray-300">
                <ol class="list-decimal">
                    <li>Memahami karakteristik bangun ruang sisi datar (kubus, balok, prisma, dan Limas)</li>
                    <li>Menggambarkan jaring-jaring kubus, balok, prisma dan Limas</li>
                    <li>Menentukan luas permukaan kubus, balok, prisma, dan Limas</li>
                    <li>Menentukan volume permukaan kubus, balok, prisma, dan Limas</li>
                    <li>Menentukan luas permukaan dan volume bangun ruang sisi datar gabungan</li>
                </ol>
            </div>
            <button
                class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium float-right rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 cursor-pointer closeModal">
                Tutup
            </button>
        </div>
    </div>

    <div id="backdrop" class="fixed inset-0 z-40"
        style="background-color:color-mix(in oklab,oklch(13% .028 261.692)70%,transparent)">
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const backdrop = document.getElementById('backdrop');
            const closeModalBtn = document.querySelectorAll('.closeModal');
            document.body.classList.add("overflow-hidden");

            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');

            closeModalBtn.forEach(button => {
                button.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove("overflow-hidden");
                });
            });
        });
    </script>
@endpush
