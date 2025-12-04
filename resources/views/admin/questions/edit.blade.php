@extends('admin.layout')

@section('title', 'Edit Soal - ' . $quiz->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Soal</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.quizzes.questions.update', [$quiz, $question]) }}"
                class="px-4 py-5 sm:p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="question_text"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pertanyaan</label>
                        <textarea name="question_text" id="question_text" rows="3" required
                            class="mt-3 block w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm p-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                            placeholder="Masukkan pertanyaan di sini...">{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Opsi Jawaban
                        </label>
                        <div class="space-y-3" id="options-container">
                            @foreach ($question->options as $index => $option)
                                <div class="flex items-center space-x-3 option-row">
                                    <input type="radio" name="correct_option" value="{{ $index }}"
                                        {{ $option->is_correct || old('correct_option') == $index ? 'checked' : '' }}
                                        class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" required>
                                    <input type="text" name="options[{{ $index }}]"
                                        value="{{ old('options.' . $index, $option->option_text) }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="Opsi {{ $index + 1 }}" required autocomplete="off">
                                    @if ($index > 1)
                                        <button type="button" onclick="removeOption(this)"
                                            class="text-red-600 hover:text-red-800 p-1 cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                            @for ($i = $question->options->count(); $i < 4; $i++)
                                @if ($i < 4)
                                    <div class="flex items-center space-x-3 option-row">
                                        <input type="radio" name="correct_option" value="{{ $i }}"
                                            {{ old('correct_option') == $i ? 'checked' : '' }}
                                            class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" required>
                                        <input type="text" name="options[{{ $i }}]"
                                            value="{{ old('options.' . $i) }}"
                                            class="flex-1 border-gray-300 dark:border-gray-600 rounded-md shadow-sm p-2 checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle sm:text-sm dark:bg-gray-700 dark:text-white"
                                            placeholder="Opsi {{ $i + 1 }}" autocomplete="off">
                                        @if ($i > 1)
                                            <button type="button" onclick="removeOption(this)"
                                                class="text-red-600 hover:text-red-800 p-1 cursor-pointer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @endfor
                        </div>

                        <div class="mt-4 flex space-x-3">
                            <button type="button" onclick="addOption()" id="add-option-btn"
                                class="text-indigo-600 hover:text-indigo-700 text-sm font-medium cursor-pointer">
                                + Tambah Opsi
                            </button>
                        </div>

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Pilih tombol di kiri untuk jawaban yang benar. Anda bisa membuat 2-6 opsi.
                        </p>

                        @error('options')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('correct_option')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.quizzes.questions.index', $quiz) }}"
                        class="bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 cursor-pointer">
                        Perbarui Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let optionCount = {{ max(4, $question->options->count()) }};

        function addOption() {
            if (optionCount >= 6) return;

            const container = document.getElementById('options-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex items-center space-x-3 option-row';

            newRow.innerHTML = `
        <input type="radio" name="correct_option" value="${optionCount}" 
               class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" required>
        <input type="text" name="options[${optionCount}]" 
               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
               placeholder="Opsi ${optionCount + 1}" autocomplete="off">
        <button type="button" onclick="removeOption(this)" 
                class="text-red-600 hover:text-red-800 p-1 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

            container.appendChild(newRow);
            optionCount++;

            updateRadioValues();

            if (optionCount >= 6) {
                document.getElementById('add-option-btn').style.display = 'none';
            }
        }

        function removeOption(button) {
            const row = button.closest('.option-row');
            const container = document.getElementById('options-container');

            if (container.children.length > 2) {
                row.remove();
                optionCount--;
                updateRadioValues();
                document.getElementById('add-option-btn').style.display = 'inline';
            }
        }

        function updateRadioValues() {
            const rows = document.querySelectorAll('.option-row');
            rows.forEach((row, index) => {
                const radio = row.querySelector('input[type="radio"]');
                const textInput = row.querySelector('input[type="text"]');

                radio.value = index;
                textInput.name = `options[${index}]`;
                textInput.placeholder = `Opsi ${index + 1}`;
            });
        }

        // Hide add button if we're at max options
        if (optionCount >= 6) {
            document.getElementById('add-option-btn').style.display = 'none';
        }
    </script>
@endsection
