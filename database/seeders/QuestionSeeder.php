<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = \App\Models\Quiz::all();

        foreach ($quizzes as $quiz) {
            // Create 5-10 questions per quiz
            \App\Models\Question::factory()
                ->count(rand(5, 10))
                ->for($quiz)
                ->create();
        }
    }
}
