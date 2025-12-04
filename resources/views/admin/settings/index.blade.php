@extends('admin.layout')

@section('title', 'Pengaturan Kuis')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pengaturan Kuis</h1>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @foreach ($settings as $groupName => $groupSettings)
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $groupName }}</h3>

                        <div class="space-y-4">
                            @foreach ($groupSettings as $key => $setting)
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        @if ($setting['type'] === 'boolean')
                                            <input id="{{ $key }}" name="{{ $key }}" type="checkbox"
                                                {{ $setting['value'] ? 'checked' : '' }}
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 p-2 dark:border-gray-600 rounded">
                                        @elseif($setting['type'] === 'number')
                                            <input id="{{ $key }}" name="{{ $key }}" type="number"
                                                value="{{ $setting['value'] }}"
                                                class="shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-20 sm:text-sm border-gray-300 p-2 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                        @else
                                            <input id="{{ $key }}" name="{{ $key }}" type="text"
                                                value="{{ $setting['value'] }}"
                                                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        @endif
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="{{ $key }}"
                                            class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ $setting['label'] }}
                                        </label>
                                        @if ($setting['description'])
                                            <p class="text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>

        <div class="mt-8 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
            <div class="text-sm text-yellow-700 dark:text-yellow-300">
                <p>Perubahan pengaturan hanya akan berlaku untuk kuis yang dibuat setelah perubahan tersebut.</p>
            </div>
        </div>
    </div>
@endsection
