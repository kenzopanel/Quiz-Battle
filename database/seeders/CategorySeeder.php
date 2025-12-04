<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Science',
            'History',
            'Geography',
            'Sports',
            'Entertainment',
            'Technology',
            'Literature',
            'Art',
            'Music',
            'Mathematics'
        ];

        foreach ($categories as $categoryName) {
            \App\Models\Category::create(['name' => $categoryName]);
        }
    }
}
