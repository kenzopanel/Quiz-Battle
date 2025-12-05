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
                class="px-4 py-5 sm:p-6" enctype="multipart/form-data">
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
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gambar (Opsional)
                        </label>
                        @if ($question->image_path)
                            <div id="existing-image-container" class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <img src="{{ $question->image_url }}" alt="Question image"
                                            class="w-full h-32 object-cover rounded">
                                    </div>
                                    <button type="button" onclick="removeExistingImage()"
                                        class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded cursor-pointer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" id="remove_image" name="remove_image" value="0">
                            </div>
                        @endif
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
                        <div id="dropzone-container"
                            @if ($question->image_path) class="mb-4 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition hidden" @else class="mb-4 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition" @endif>
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Klik atau seret gambar di sini
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 text-center mt-1">Maks 1MB | JPEG, PNG, GIF,
                                WebP</p>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" />
                        @error('image')
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

            const container = document.querySelector('#options-container');
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
                document.querySelector('#add-option-btn').style.display = 'none';
            }
        }

        function removeOption(button) {
            const row = button.closest('.option-row');
            const container = document.querySelector('#options-container');

            if (container.children.length > 2) {
                row.remove();
                optionCount--;
                updateRadioValues();
                document.querySelector('#add-option-btn').style.display = 'inline';
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

        if (optionCount >= 6) {
            document.querySelector('#add-option-btn').style.display = 'none';
        }

        function removeExistingImage() {
            document.querySelector('#remove_image').value = '1';
            document.querySelector('#existing-image-container').classList.add('hidden');
            document.querySelector('#dropzone-container').classList.remove('hidden');
        }

        document.querySelector('#image').addEventListener('change', function(e) {
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
                    document.querySelector('#image-preview').src = event.target.result;
                    document.querySelector('#image-preview-container').classList.remove('hidden');
                    document.querySelector('#dropzone-container').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function clearImagePreview() {
            document.querySelector('#image').value = '';
            document.querySelector('#image-preview-container').classList.add('hidden');
            document.querySelector('#dropzone-container').classList.remove('hidden');
            document.querySelector('#image-preview').src = '';
            document.querySelector('#remove_image').value = '0';
        }

        const dropzoneContainer = document.querySelector('#dropzone-container');
        if (dropzoneContainer) {
            dropzoneContainer.addEventListener('click', () => {
                document.querySelector('#image').click();
            });

            dropzoneContainer.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzoneContainer.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
            });

            dropzoneContainer.addEventListener('dragleave', () => {
                dropzoneContainer.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
            });

            dropzoneContainer.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzoneContainer.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.querySelector('#image').files = files;
                    const event = new Event('change', {
                        bubbles: true
                    });
                    document.querySelector('#image').dispatchEvent(event);
                }
            });
        }
    </script>
@endsection
