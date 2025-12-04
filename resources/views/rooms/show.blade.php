@extends('layouts.app')

@section('title', $room->name)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Ruang Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $room->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-300">{{ $room->category->name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kode Ruang</div>
                    <div class="text-xl font-mono font-bold text-blue-600 dark:text-blue-400">{{ $room->code }}</div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $room->status === 'waiting' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        {{ count($room->player_tokens ?? []) }}/{{ $room->max_players }} pemain
                    </span>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Berakhir dalam {{ $room->expires_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Players List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pemain di Ruang</h2>

                    <div class="space-y-3">
                        @forelse ($room->player_tokens ?? [] as $index => $playerToken)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            @if ($room->creator_session_token === $playerToken)
                                                Pemain {{ $index + 1 }} (Creator)
                                            @elseif ($sessionToken === $playerToken)
                                                Kamu
                                            @else
                                                Pemain {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($playerToken, 8) }}...
                                        </div>
                                    </div>
                                </div>
                                @if ($room->creator_session_token === $playerToken)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
                                        👑 Creator
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Belum ada pemain di room</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($room->status === 'waiting' && count($room->player_tokens ?? []) < $room->max_players)
                        <div
                            class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-blue-800 dark:text-blue-200 text-sm">
                                💡 Bagikan kode room <strong>{{ $room->code }}</strong> kepada teman untuk bergabung!
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ruang Actions -->
            <div class="space-y-6">
                @if ($room->status === 'waiting')
                    @if ($isCreator)
                        <!-- Creator Actions -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kontrol Ruang</h3>

                            @if (count($room->player_tokens ?? []) >= 2)
                                <form action="{{ route('rooms.start', $room->code) }}" method="POST" class="mb-4">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 cursor-pointer">
                                        Mulai Battle
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed mb-4">
                                    Butuh minimal 2 pemain
                                </button>
                            @endif

                            <form action="{{ route('rooms.leave', $room->code) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer"
                                    onclick="return confirm('Yakin ingin menghapus ruang?')">
                                    Hapus Ruang
                                </button>
                            </form>
                        </div>
                    @elseif ($isPlayer)
                        <!-- Player Actions -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Pemain</h3>

                            <div
                                class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                                    Menunggu creator memulai...
                                </p>
                            </div>

                            <form action="{{ route('rooms.leave', $room->code) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                    Keluar dari Ruang
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Non-player Actions -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Gabung Ruang</h3>

                            @if ($room->isFull())
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed mb-4">
                                    Ruang Penuh
                                </button>
                            @else
                                <form action="{{ route('rooms.join.request') }}" method="POST" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="code" value="{{ $room->code }}">
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                                        🎮 Gabung Ruang
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('rooms.index') }}"
                                class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-colors duration-200">
                                ← Kembali
                            </a>
                        </div>
                    @endif
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Ruang</h3>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-center">
                            <p class="text-gray-600 dark:text-gray-300">Ruang ini sedang {{ $room->status }}</p>
                        </div>
                    </div>
                @endif

                <!-- Ruang Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Info Ruang</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kategori:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $room->category->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $room->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Berakhir:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $room->expires_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Setup Echo listener for room updates
                if (window.Echo && window.sessionToken) {
                    window.Echo.channel(`room.{{ $room->code }}`)
                        .listen('.player.joined', (e) => {
                            console.log('Player joined!', e);
                            // Refresh page to show new player
                            location.reload();
                        })
                        .listen('.room.started', (e) => {
                            console.log('Ruang started!', e);
                            // Redirect to battle
                            window.location.href = `/battle/${e.battle_id}`;
                        });
                }
            });
        </script>
    @endpush
@endsection
