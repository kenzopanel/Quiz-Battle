<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();

        foreach ($categories as $category) {
            // Create 2-3 quizzes per category
            \App\Models\Quiz::factory()
                ->count(rand(2, 3))
                ->for($category)
                ->create();
        }
    }
}
