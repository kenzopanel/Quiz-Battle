@extends('admin.layout')

@section('title', 'Buat Soal - ' . $quiz->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}" class="px-4 py-5 sm:p-6"
                enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="question_text"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pertanyaan</label>
                        <textarea name="question_text" id="question_text" rows="3" required
                            class="mt-3 block w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm p-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                            placeholder="Masukkan pertanyaan di sini...">{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gambar (Opsional)
                        </label>
                        <div id="image-preview-container" class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <img id="image-preview" src="" alt="Preview"
                                        class="w-full h-32 object-cover rounded">
                                </div>
                                <button type="button" onclick="clearImagePreview()"
                                    class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded cursor-pointer">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="dropzone-container" class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2"><span
                                            class="font-semibold">Upload Gambar</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WebP (max 1MB)</p>
                                </div>
                                <input id="image" type="file" name="image" class="hidden" accept="image/*">
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Opsi Jawaban
                        </label>
                        <div class="space-y-3" id="options-container">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="flex items-center space-x-3 option-row">
                                    <input type="radio" name="correct_option" value="{{ $i }}"
                                        {{ old('correct_option') == $i ? 'checked' : '' }}
                                        class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" required>
                                    <input type="text" name="options[{{ $i }}]"
                                        value="{{ old('options.' . $i) }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="Opsi {{ $i + 1 }}" required autocomplete="off">
                                    @if ($i > 1)
                                        <button type="button" onclick="removeOption(this)"
                                            class="text-red-600 hover:text-red-800 p-1 cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endfor
                        </div>

                        <div class="mt-4 flex space-x-3">
                            <button type="button" onclick="addOption()" id="add-option-btn"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium cursor-pointer">
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
                        Buat Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let optionCount = 4;

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
               placeholder="Opsi ${optionCount + 1}" required autocomplete="off">
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

        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 1024 * 1024; // 1MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                if (file.size > maxSize) {
                    alert('File size exceeds 1MB. Please choose a smaller image.');
                    e.target.value = '';
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid image format. Please upload JPEG, PNG, GIF, or WebP.');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('image-preview').src = event.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                    document.getElementById('dropzone-container').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function clearImagePreview() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('dropzone-container').classList.remove('hidden');
            document.getElementById('image-preview').src = '';
        }
    </script>
@endsection
