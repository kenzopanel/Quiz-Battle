@extends('layouts.app')

@section('title', 'Ruang Publik')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ruang Publik</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Gabung dengan ruang yang sudah ada</p>
            </div>
        </div>

        @if ($rooms->count() > 0)
            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($rooms as $room)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $room->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $room->category->name }}</p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200">
                                {{ count($room->player_tokens ?? []) }}/{{ $room->max_players }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $room->code }}</span>
                            <span>{{ $room->created_at->diffForHumans() }}</span>
                        </div>

                        @if ($room->isFull())
                            <button disabled
                                class="w-full bg-gray-300 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                Ruang Penuh
                            </button>
                        @else
                            <form action="{{ route('rooms.join.request') }}" method="POST">
                                @csrf
                                <input type="hidden" name="code" value="{{ $room->code }}">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                    Gabung Ruang
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $rooms->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div
                    class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-gray-400 text-4xl">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                        </svg>
                    </span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada ruang tersedia</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Buat ruang pertamamu!</p>
                <div class="flex flex-center gap-2 justify-center">
                    <a href="{{ route('rooms.create') }}"
                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Buat Ruang
                    </a>
                    <a href="{{ route('index') }}"
                        class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Kembali
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
