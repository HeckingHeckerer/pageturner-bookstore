<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed categories first
        $this->call(CategorySeeder::class);
        
        // Seed books
        $this->call(BookSeeder::class);
        
        // Create admin user with specific credentials
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 's.villarino.victorykyle@cmu.edu.ph',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(), // Skip email verification
        ]);

        // Create customer users
        $customers = User::factory(10)->create(['role' => 'customer']);

        // Create reviews for books
        $books = \App\Models\Book::all();

        foreach ($customers as $customer) {
            // Each customer reviews 3-5 random books
            $books->random(rand(3, 5))->unique()->each(function ($book) use ($customer) {
                Review::factory()->create([
                    'user_id' => $customer->id,
                    'book_id' => $book->id,
                ]);
            });
        }
    }
}
