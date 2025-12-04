<?php

namespace App\Http\Controllers;

use App\Models\Category;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::whereHas('quizzes')->withCount('quizzes')->get();

        return view('index', compact('categories'));
    }
}
