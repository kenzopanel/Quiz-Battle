<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => Category::count(),
            'quizzes' => Quiz::count(),
            'questions' => Question::count(),
            'options' => QuestionOption::count(),
        ];

        $recentQuizzes = Quiz::with('category')
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $categoriesWithQuizCount = Category::withCount('quizzes')
            ->orderBy('quizzes_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentQuizzes', 'categoriesWithQuizCount'));
    }
}
