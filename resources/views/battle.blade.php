@extends('layouts.app')

@section('title', 'Pertarungan Kuis')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Battle Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-300">{{ $quiz->category->name }}</p>
                </div>

                <!-- Battle Timer -->
                <div id="battle-timer" class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Batas Waktu</div>
                    <div id="total-time" class="text-2xl font-bold text-red-600 dark:text-red-400">--:--</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Progress</span>
                    <span id="question-counter" class="text-sm text-gray-600 dark:text-gray-400">0 /
                        {{ $quiz->questions->count() }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Waiting for Battle Start -->
        <div id="waiting-screen"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div
                class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white text-2xl animate-pulse">⚔️</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mempersiapkan Pertarungan</h2>
            <p class="text-gray-600 dark:text-gray-300">Menunggu semua pemain bergabung...</p>

            <div class="mt-6">
                <div
                    class="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <span class="text-blue-600 dark:text-blue-400">Bergabung dalam pertarungan...</span>
                </div>
            </div>
        </div>

        <!-- Quiz Content -->
        <div id="quiz-screen" class="hidden">
            <!-- Current Question -->
            <div id="question-card"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <!-- Question Timer -->
                <div class="flex items-center justify-between mb-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Soal <span id="current-question-num">1</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Waktu Tersisa</div>
                            <div id="question-timer" class="text-xl font-bold text-orange-600 dark:text-orange-400">--</div>
                        </div>
                        <div class="w-12 h-12 relative">
                            <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                    fill="none" class="text-gray-200 dark:text-gray-700" />
                                <circle id="timer-circle" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round"
                                    class="text-orange-600 dark:text-orange-400 transition-all duration-1000"
                                    style="stroke-dasharray: 62.83; stroke-dashoffset: 0" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="mb-8">
                    <h3 id="question-text" class="text-xl font-semibold text-gray-900 dark:text-white leading-relaxed">
                        Memuat soal...
                    </h3>
                </div>

                <!-- Answer Options -->
                <div id="answer-options" class="grid grid-cols-1 gap-3">
                    <!-- Options will be populated by JavaScript -->
                </div>
            </div>

            <!-- Score Display -->
            <div
                class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Skor Kamu</div>
                            <div id="your-score" class="text-2xl font-bold text-green-600 dark:text-green-400">0</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Jawaban Benar</div>
                            <div id="correct-count" class="text-lg font-semibold text-gray-900 dark:text-white">0</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Waktu</div>
                            <div id="avg-time" class="text-lg font-semibold text-gray-900 dark:text-white">0s</div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Status Pertarungan</div>
                        <div id="battle-status" class="text-lg font-semibold text-blue-600 dark:text-blue-400">Dalam Proses
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Screen -->
        <div id="results-screen"
            class="hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div id="winner-announcement" class="mb-8">
                <!-- Will be populated by JavaScript -->
            </div>

            <div id="final-scores" class="mb-8">
                <!-- Will be populated by JavaScript -->
            </div>

            <div class="flex justify-center space-x-3">
                <a href="{{ route('index') }}"
                    class="bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const battleId = @json($battleId);
                const sessionToken = @json($sessionToken);
                const quizData = @json($quiz);

                let currentQuestionIndex = 0;
                let userAnswers = [];
                let questionStartTime = 0;
                let totalStartTime = 0;
                let totalCorrect = 0;
                let totalTime = 0;
                let questionTimer = null;
                let totalTimer = null;
                let battleEnded = false;

                const elements = {
                    waitingScreen: document.getElementById('waiting-screen'),
                    quizScreen: document.getElementById('quiz-screen'),
                    resultsScreen: document.getElementById('results-screen'),
                    questionText: document.getElementById('question-text'),
                    answerOptions: document.getElementById('answer-options'),
                    currentQuestionNum: document.getElementById('current-question-num'),
                    questionCounter: document.getElementById('question-counter'),
                    progressBar: document.getElementById('progress-bar'),
                    questionTimer: document.getElementById('question-timer'),
                    timerCircle: document.getElementById('timer-circle'),
                    totalTime: document.getElementById('total-time'),
                    yourScore: document.getElementById('your-score'),
                    correctCount: document.getElementById('correct-count'),
                    avgTime: document.getElementById('avg-time'),
                    battleStatus: document.getElementById('battle-status')
                };

                // Anti-cheat configuration
                const antiCheatConfig = {
                    enabled: @json(config('quiz.anti_cheat.enabled')),
                    tabSwitching: @json(config('quiz.anti_cheat.tab_switching')),
                    navigationProtection: @json(config('quiz.anti_cheat.navigation_protection')),
                    pageUnload: @json(config('quiz.anti_cheat.page_unload')),
                    confirmationDialog: @json(config('quiz.anti_cheat.confirmation_dialog')),
                    triggerDelay: @json(config('quiz.anti_cheat.trigger_delay'))
                };

                // Anti-cheat measures
                if (antiCheatConfig.enabled) {
                    setupAntiCheat();
                }

                // Initialize battle
                joinBattle();

                function setupAntiCheat() {
                    // Tab switching detection
                    if (antiCheatConfig.tabSwitching) {
                        document.addEventListener('visibilitychange', function() {
                            if (document.hidden && !battleEnded) {
                                setTimeout(() => {
                                    if (document.hidden && !battleEnded) {
                                        autoLose('tab_switched');
                                    }
                                }, antiCheatConfig.triggerDelay);
                            }
                        });
                    }

                    // Back button / navigation protection
                    if (antiCheatConfig.navigationProtection) {
                        window.addEventListener('beforeunload', function(e) {
                            if (!battleEnded) {
                                autoLose('page_unload');
                                if (antiCheatConfig.confirmationDialog) {
                                    const message =
                                        'Keluar dari pertarungan akan membuatmu kalah. Apakah kamu yakin?';
                                    e.preventDefault();
                                    e.returnValue = message;
                                    return message;
                                }
                            }
                        });
                    }

                    // Page unload
                    if (antiCheatConfig.pageUnload) {
                        window.addEventListener('unload', function() {
                            if (!battleEnded) {
                                navigator.sendBeacon(`/api/battle/${battleId}/auto-lose`, JSON.stringify({
                                    reason: 'page_closed',
                                    _token: window.csrfToken
                                }));
                            }
                        });
                    }
                }

                function autoLose(reason) {
                    if (battleEnded) return;

                    fetch(`/api/battle/${battleId}/auto-lose`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        body: JSON.stringify({
                            reason
                        })
                    }).then(() => {
                        battleEnded = true;
                        showResults('Kamu kalah karena ' + reason.replace('_', ' '), {});
                    });
                }

                function joinBattle() {
                    fetch(`/api/battle/${battleId}/join`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.csrfToken
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                setupWebSocketListeners();
                            }
                        }).catch(error => {
                            console.error('Error joining battle:', error);
                        });
                }

                function setupWebSocketListeners() {
                    if (!window.Echo) return;

                    window.Echo.channel(`battle.${battleId}`)
                        .listen('.battle.started', (e) => {
                            console.log('Battle started!', e);
                            startBattle(e);
                        })
                        .listen('.battle.ended', (e) => {
                            console.log('Battle ended!', e);
                            endBattle(e);
                        });
                }

                function startBattle(data) {
                    elements.waitingScreen.classList.add('hidden');
                    elements.quizScreen.classList.remove('hidden');

                    totalStartTime = Date.now();
                    setupTotalTimer(data.quiz.timeout_seconds);

                    // Store quiz questions
                    window.quizQuestions = data.quiz.questions;

                    showQuestion(0);
                }

                function setupTotalTimer(totalSeconds) {
                    let remaining = totalSeconds;

                    totalTimer = setInterval(() => {
                        remaining--;
                        const minutes = Math.floor(remaining / 60);
                        const seconds = remaining % 60;
                        elements.totalTime.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                        if (remaining <= 0) {
                            clearInterval(totalTimer);
                            submitFinalScore();
                        }
                    }, 1000);
                }

                function showQuestion(index) {
                    if (!window.quizQuestions || index >= window.quizQuestions.length) {
                        submitFinalScore();
                        return;
                    }

                    currentQuestionIndex = index;
                    const question = window.quizQuestions[index];

                    // Update UI
                    elements.currentQuestionNum.textContent = index + 1;
                    elements.questionCounter.textContent = `${index + 1} / ${window.quizQuestions.length}`;
                    elements.progressBar.style.width = `${((index + 1) / window.quizQuestions.length) * 100}%`;
                    elements.questionText.textContent = question.question_text;

                    // Clear previous options
                    elements.answerOptions.innerHTML = '';

                    // Add new options
                    question.options.forEach((option, optionIndex) => {
                        const button = document.createElement('button');
                        button.className =
                            'w-full p-4 text-left text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-200 dark:border-gray-600 rounded-lg transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600 cursor-pointer';
                        button.textContent = option.option_text;
                        button.dataset.optionId = option.id;
                        button.dataset.isCorrect = option.is_correct;

                        button.addEventListener('click', () => selectAnswer(option, button));

                        elements.answerOptions.appendChild(button);
                    });

                    // Start question timer
                    questionStartTime = Date.now();
                    startQuestionTimer(quizData.per_question_time);
                }

                function startQuestionTimer(seconds) {
                    let remaining = seconds;
                    const circumference = 2 * Math.PI * 10; // radius = 10

                    if (questionTimer) clearInterval(questionTimer);

                    questionTimer = setInterval(() => {
                        elements.questionTimer.textContent = remaining;

                        // Update circular progress
                        const progress = remaining / seconds;
                        const offset = circumference * (1 - progress);
                        elements.timerCircle.style.strokeDashoffset = offset;

                        remaining--;

                        if (remaining < 0) {
                            clearInterval(questionTimer);
                            selectAnswer(null, null); // Auto-submit as wrong
                        }
                    }, 1000);
                }

                function selectAnswer(selectedOption, button) {
                    if (questionTimer) clearInterval(questionTimer);

                    const timeSpent = Date.now() - questionStartTime;
                    const isCorrect = selectedOption?.is_correct || false;

                    // Record answer
                    userAnswers.push({
                        questionId: window.quizQuestions[currentQuestionIndex].id,
                        selectedOptionId: selectedOption?.id || null,
                        isCorrect: isCorrect,
                        timeSpent: timeSpent
                    });

                    // Update stats
                    if (isCorrect) {
                        totalCorrect++;
                    }
                    totalTime += timeSpent;

                    // Update UI
                    updateStatsDisplay();

                    // Visual feedback
                    if (button) {
                        button.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-white',
                            'hover:bg-blue-50', 'dark:hover:bg-blue-900/20', 'border-gray-200',
                            'dark:border-gray-600', 'hover:border-blue-300', 'dark:hover:border-blue-600');

                        if (isCorrect) {
                            button.classList.add('bg-green-950', 'hover:bg-green-950', 'border-green-500',
                                'text-white');
                        } else {
                            button.classList.add('bg-red-950', 'hover:bg-red-950', 'border-red-500', 'text-white');
                            document.querySelectorAll('[data-is-correct=\"true\"]').forEach(correctBtn => {
                                correctBtn.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'text-gray-900',
                                    'dark:text-white', 'hover:bg-blue-50', 'dark:hover:bg-blue-900/20',
                                    'border-gray-200', 'dark:border-gray-600', 'hover:border-blue-300',
                                    'dark:hover:border-blue-600');
                                correctBtn.classList.add('bg-green-950', 'hover:bg-green-950',
                                    'border-green-500', 'text-white', 'correct-answer');
                            });
                        }
                    }

                    // Disable all options
                    document.querySelectorAll('#answer-options button').forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('cursor-not-allowed');
                        if (!btn.classList.contains('correct-answer')) {
                            btn.classList.add('opacity-50');
                        }
                    });

                    // Move to next question after delay
                    setTimeout(() => {
                        if (currentQuestionIndex + 1 < window.quizQuestions.length) {
                            showQuestion(currentQuestionIndex + 1);
                        } else {
                            submitFinalScore();
                        }
                    }, 2000);
                }

                function updateStatsDisplay() {
                    elements.yourScore.textContent = totalCorrect;
                    elements.correctCount.textContent = totalCorrect;

                    if (userAnswers.length > 0) {
                        const avgTime = totalTime / userAnswers.length / 1000;
                        elements.avgTime.textContent = avgTime.toFixed(1) + 's';
                    }
                }

                function submitFinalScore() {
                    if (battleEnded) return;
                    battleEnded = true;

                    if (totalTimer) clearInterval(totalTimer);
                    if (questionTimer) clearInterval(questionTimer);

                    fetch(`/api/battle/${battleId}/submit-score`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.csrfToken
                            },
                            body: JSON.stringify({
                                score: totalCorrect,
                                total_time_ms: totalTime
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                elements.battleStatus.textContent = 'Menunggu lawanmu...';
                            }
                        }).catch(error => {
                            console.error('Error submitting score:', error);
                        });
                }

                function endBattle(data) {
                    battleEnded = true;

                    if (totalTimer) clearInterval(totalTimer);
                    if (questionTimer) clearInterval(questionTimer);

                    let message;
                    if (data.winner === null) {
                        message = 'Seri!';
                    } else if (data.winner === sessionToken) {
                        message = 'Kamu Menang!';
                    } else {
                        message = 'Kamu Kalah!';
                    }

                    showResults(message, data.scores);
                }

                function showResults(message, scores) {
                    elements.quizScreen.classList.add('hidden');
                    elements.resultsScreen.classList.remove('hidden');

                    // Winner announcement
                    const isWinner = message.includes('Menang');
                    const isDraw = message.includes('Seri');

                    let bgColor, icon, textColor;
                    if (isDraw) {
                        bgColor = 'from-yellow-500 to-amber-500';
                        icon = '🤝';
                        textColor = 'text-yellow-600 dark:text-yellow-400';
                    } else if (isWinner) {
                        bgColor = 'from-green-500 to-emerald-500';
                        icon = '🏆';
                        textColor = 'text-green-600 dark:text-green-400';
                    } else {
                        bgColor = 'from-red-500 to-rose-500';
                        icon = '😔';
                        textColor = 'text-red-600 dark:text-red-400';
                    }

                    document.getElementById('winner-announcement').innerHTML = `
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r ${bgColor} rounded-full flex items-center justify-center">
                <span class="text-white text-3xl">${icon}</span>
            </div>
            <h2 class="text-3xl font-bold ${textColor} mb-2">
                ${message}
            </h2>
        `;

                    // Final scores
                    document.getElementById('final-scores').innerHTML = `
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hasil Akhir</h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${totalCorrect}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Jawaban Benar</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${(totalTime / 1000).toFixed(1)}s</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Waktu</div>
                    </div>
                </div>
            </div>
        `;
                }
            });
        </script>
    @endpush
@endsection
