<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
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
        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
        ]);

        // Create options
        foreach ($request->options as $index => $optionText) {
            if (!empty($optionText)) {
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
        $question->update([
            'question_text' => $request->question_text,
        ]);

        // Delete existing options and create new ones
        $question->options()->delete();

        foreach ($request->options as $index => $optionText) {
            if (!empty($optionText)) {
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
        $question->delete();

        return redirect()->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Question deleted successfully!');
    }
}
