<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    public function __construct(private QuestionService $questionService) {}

    public function index(Quiz $quiz)
    {
        $quiz->load(['questions.options', 'category']);

        return view('admin.questions.index', compact('quiz'));
    }

    public function create(Quiz $quiz)
    {
        return view('admin.questions.create', compact('quiz'));
    }

    public function store(StoreQuestionRequest $request, Quiz $quiz)
    {
        $data = $request->validated();
        $data['quiz'] = $quiz;

        $question = $this->questionService->storeQuestion($data);

        foreach ($request->options as $index => $optionText) {
            if (! empty($optionText)) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => $request->correct_option == $index,
                ]);
            }
        }

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question created successfully!');
    }

    public function show(Quiz $quiz, Question $question)
    {
        $question->load('options');

        return view('admin.questions.show', compact('quiz', 'question'));
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $question->load('options');

        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(StoreQuestionRequest $request, Quiz $quiz, Question $question)
    {
        $data = $request->validated();

        // If remove_image is set but no new image uploaded, set image to null
        if ($request->input('remove_image') == '1' && ! isset($data['image'])) {
            $data['image'] = null;
        }

        $this->questionService->updateQuestion($question, $data);

        $question->options()->delete();

        foreach ($request->options as $index => $optionText) {
            if (! empty($optionText)) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => $request->correct_option == $index,
                ]);
            }
        }

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question updated successfully!');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        $this->questionService->deleteQuestion($question);

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question deleted successfully!');
    }
}
