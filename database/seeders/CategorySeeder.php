<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiction',
                'description' => 'Imaginative storytelling that explores human experiences, emotions, and the complexities of life through invented characters and plots.'
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Literature based on facts, real events, and actual people, providing information and insights about the world.'
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Speculative fiction that explores futuristic concepts, advanced technology, space exploration, and their impact on society.'
            ],
            [
                'name' => 'Mystery & Thriller',
                'description' => 'Suspenseful stories involving crimes, investigations, and puzzles that keep readers guessing until the very end.'
            ],
            [
                'name' => 'Romance',
                'description' => 'Stories centered around love, relationships, and emotional connections between characters.'
            ],
            [
                'name' => 'Biography & Memoir',
                'description' => 'True stories about real people\'s lives, achievements, and personal experiences.'
            ],
            [
                'name' => 'History',
                'description' => 'Books that explore past events, civilizations, and historical figures to understand our collective heritage.'
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Guides and resources designed to help individuals improve their lives, skills, and personal development.'
            ],
            [
                'name' => 'Technology',
                'description' => 'Books covering programming, software development, IT, and the latest technological advancements.'
            ],
            [
                'name' => 'Children\'s Books',
                'description' => 'Age-appropriate literature designed to educate, entertain, and inspire young readers.'
            ],
            [
                'name' => 'Business & Finance',
                'description' => 'Books about entrepreneurship, management, investing, and financial literacy.'
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Literature focused on physical and mental health, fitness, nutrition, and overall well-being.'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}