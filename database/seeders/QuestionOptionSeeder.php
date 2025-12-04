<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = \App\Models\Question::all();

        foreach ($questions as $question) {
            // Create 4 options per question, 1 correct and 3 incorrect
            \App\Models\QuestionOption::factory()
                ->count(3)
                ->for($question)
                ->create();

            // Create one correct option
            \App\Models\QuestionOption::factory()
                ->correct()
                ->for($question)
                ->create();
        }
    }
}
