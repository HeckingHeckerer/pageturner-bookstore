<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all category IDs
        $categories = DB::table('categories')->pluck('id', 'name');

        $books = [
            // Fiction
            [
                'category_id' => $categories['Fiction'],
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780061120084',
                'price' => 12.99,
                'stock_quantity' => 25,
                'description' => 'A gripping tale of racial injustice and childhood innocence in the American South during the 1930s.'
            ],
            [
                'category_id' => $categories['Fiction'],
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'price' => 11.99,
                'stock_quantity' => 20,
                'description' => 'A dystopian vision of a totalitarian future where individuality is crushed and Big Brother watches everyone.'
            ],
            [
                'category_id' => $categories['Fiction'],
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '9780141439518',
                'price' => 9.99,
                'stock_quantity' => 15,
                'description' => 'A classic tale of love, class, and social expectations in Regency-era England.'
            ],
            [
                'category_id' => $categories['Fiction'],
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '9780743273565',
                'price' => 10.99,
                'stock_quantity' => 18,
                'description' => 'A tragic story of wealth, love, and the American Dream set in the Roaring Twenties.'
            ],
            [
                'category_id' => $categories['Fiction'],
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'isbn' => '9780316769488',
                'price' => 10.49,
                'stock_quantity' => 22,
                'description' => 'A coming-of-age story following Holden Caulfield\'s journey through teenage alienation and angst.'
            ],

            // Non-Fiction
            [
                'category_id' => $categories['Non-Fiction'],
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'price' => 16.99,
                'stock_quantity' => 30,
                'description' => 'A fascinating exploration of human history from the Stone Age to the modern era.'
            ],
            [
                'category_id' => $categories['Non-Fiction'],
                'title' => 'Educated',
                'author' => 'Tara Westover',
                'isbn' => '9780399590504',
                'price' => 15.99,
                'stock_quantity' => 28,
                'description' => 'A memoir about a woman who escapes her survivalist family to pursue education.'
            ],
            [
                'category_id' => $categories['Non-Fiction'],
                'title' => 'Becoming',
                'author' => 'Michelle Obama',
                'isbn' => '9781524763138',
                'price' => 19.99,
                'stock_quantity' => 35,
                'description' => 'The intimate, powerful memoir of the former First Lady of the United States.'
            ],
            [
                'category_id' => $categories['Non-Fiction'],
                'title' => 'The Immortal Life of Henrietta Lacks',
                'author' => 'Rebecca Skloot',
                'isbn' => '9781400052172',
                'price' => 14.99,
                'stock_quantity' => 20,
                'description' => 'The story of a woman whose cells revolutionized medicine without her knowledge.'
            ],
            [
                'category_id' => $categories['Non-Fiction'],
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'isbn' => '9781451648539',
                'price' => 17.99,
                'stock_quantity' => 25,
                'description' => 'The definitive biography of Apple co-founder Steve Jobs.'
            ],

            // Science Fiction
            [
                'category_id' => $categories['Science Fiction'],
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '9780441172719',
                'price' => 13.99,
                'stock_quantity' => 20,
                'description' => 'An epic tale of politics, religion, and ecology on the desert planet Arrakis.'
            ],
            [
                'category_id' => $categories['Science Fiction'],
                'title' => 'Neuromancer',
                'author' => 'William Gibson',
                'isbn' => '9780441569565',
                'price' => 12.49,
                'stock_quantity' => 15,
                'description' => 'The groundbreaking cyberpunk novel that coined the term "cyberspace".'
            ],
            [
                'category_id' => $categories['Science Fiction'],
                'title' => 'The Martian',
                'author' => 'Andy Weir',
                'isbn' => '9780553418026',
                'price' => 11.99,
                'stock_quantity' => 30,
                'description' => 'A gripping survival story of an astronaut stranded on Mars.'
            ],
            [
                'category_id' => $categories['Science Fiction'],
                'title' => 'Foundation',
                'author' => 'Isaac Asimov',
                'isbn' => '9780553803716',
                'price' => 10.99,
                'stock_quantity' => 18,
                'description' => 'The first book in Asimov\'s legendary Foundation series about psychohistory and galactic empires.'
            ],
            [
                'category_id' => $categories['Science Fiction'],
                'title' => 'Ender\'s Game',
                'author' => 'Orson Scott Card',
                'isbn' => '9780812550702',
                'price' => 11.49,
                'stock_quantity' => 22,
                'description' => 'A young boy is trained to become a military commander in a war against an alien species.'
            ],

            // Mystery & Thriller
            [
                'category_id' => $categories['Mystery & Thriller'],
                'title' => 'The Girl with the Dragon Tattoo',
                'author' => 'Stieg Larsson',
                'isbn' => '9780307949486',
                'price' => 12.99,
                'stock_quantity' => 25,
                'description' => 'A dark and gripping mystery involving corporate corruption and family secrets.'
            ],
            [
                'category_id' => $categories['Mystery & Thriller'],
                'title' => 'Gone Girl',
                'author' => 'Gillian Flynn',
                'isbn' => '9780307588371',
                'price' => 13.49,
                'stock_quantity' => 28,
                'description' => 'A psychological thriller about a marriage gone terribly wrong.'
            ],
            [
                'category_id' => $categories['Mystery & Thriller'],
                'title' => 'The Da Vinci Code',
                'author' => 'Dan Brown',
                'isbn' => '9780307277671',
                'price' => 12.49,
                'stock_quantity' => 30,
                'description' => 'A fast-paced thriller involving secret societies, art history, and religious mysteries.'
            ],
            [
                'category_id' => $categories['Mystery & Thriller'],
                'title' => 'Big Little Lies',
                'author' => 'Liane Moriarty',
                'isbn' => '9780399158425',
                'price' => 13.99,
                'stock_quantity' => 20,
                'description' => 'A story of friendship, secrets, and domestic violence among three women.'
            ],
            [
                'category_id' => $categories['Mystery & Thriller'],
                'title' => 'The Silent Patient',
                'author' => 'Alex Michaelides',
                'isbn' => '9781250301697',
                'price' => 14.99,
                'stock_quantity' => 35,
                'description' => 'A psychological thriller about a woman who stops speaking after a shocking act of violence.'
            ],

            // Romance
            [
                'category_id' => $categories['Romance'],
                'title' => 'The Notebook',
                'author' => 'Nicholas Sparks',
                'isbn' => '9780446605464',
                'price' => 10.99,
                'stock_quantity' => 20,
                'description' => 'A timeless love story spanning decades and overcoming all obstacles.'
            ],
            [
                'category_id' => $categories['Romance'],
                'title' => 'Me Before You',
                'author' => 'Jojo Moyes',
                'isbn' => '9780698199985',
                'price' => 12.99,
                'stock_quantity' => 25,
                'description' => 'A heartwarming and heartbreaking story about love, choice, and living life to the fullest.'
            ],
            [
                'category_id' => $categories['Romance'],
                'title' => 'Outlander',
                'author' => 'Diana Gabaldon',
                'isbn' => '9780440212560',
                'price' => 13.99,
                'stock_quantity' => 18,
                'description' => 'A time-traveling romance set in 18th-century Scotland.'
            ],
            [
                'category_id' => $categories['Romance'],
                'title' => 'It Ends with Us',
                'author' => 'Colleen Hoover',
                'isbn' => '9781501110368',
                'price' => 14.49,
                'stock_quantity' => 30,
                'description' => 'A powerful story about love, abuse, and breaking the cycle.'
            ],
            [
                'category_id' => $categories['Romance'],
                'title' => 'The Hating Game',
                'author' => 'Sally Thorne',
                'isbn' => '9780062834572',
                'price' => 11.99,
                'stock_quantity' => 22,
                'description' => 'A delightful enemies-to-lovers office romance.'
            ],

            // Biography & Memoir
            [
                'category_id' => $categories['Biography & Memoir'],
                'title' => 'Long Walk to Freedom',
                'author' => 'Nelson Mandela',
                'isbn' => '9780316017544',
                'price' => 16.99,
                'stock_quantity' => 15,
                'description' => 'The autobiography of Nelson Mandela, chronicling his life and struggle against apartheid.'
            ],
            [
                'category_id' => $categories['Biography & Memoir'],
                'title' => 'I Know Why the Caged Bird Sings',
                'author' => 'Maya Angelou',
                'isbn' => '9780375507892',
                'price' => 13.99,
                'stock_quantity' => 20,
                'description' => 'Maya Angelou\'s powerful memoir about overcoming trauma and finding her voice.'
            ],
            [
                'category_id' => $categories['Biography & Memoir'],
                'title' => 'The Diary of a Young Girl',
                'author' => 'Anne Frank',
                'isbn' => '9780553296989',
                'price' => 8.99,
                'stock_quantity' => 25,
                'description' => 'Anne Frank\'s diary written while hiding from the Nazis during World War II.'
            ],
            [
                'category_id' => $categories['Biography & Memoir'],
                'title' => 'Wild: From Lost to Found on the Pacific Crest Trail',
                'author' => 'Cheryl Strayed',
                'isbn' => '9780307455766',
                'price' => 14.99,
                'stock_quantity' => 18,
                'description' => 'A memoir about hiking the Pacific Crest Trail as a way to heal from personal tragedy.'
            ],
            [
                'category_id' => $categories['Biography & Memoir'],
                'title' => 'Born a Crime: Stories from a South African Childhood',
                'author' => 'Trevor Noah',
                'isbn' => '9780399588174',
                'price' => 15.99,
                'stock_quantity' => 28,
                'description' => 'Trevor Noah\'s memoir about growing up in apartheid South Africa.'
            ],

            // History
            [
                'category_id' => $categories['History'],
                'title' => 'A People\'s History of the United States',
                'author' => 'Howard Zinn',
                'isbn' => '9780062397348',
                'price' => 18.99,
                'stock_quantity' => 20,
                'description' => 'A groundbreaking history of America told from the perspective of ordinary people.'
            ],
            [
                'category_id' => $categories['History'],
                'title' => 'Guns, Germs, and Steel: The Fates of Human Societies',
                'author' => 'Jared Diamond',
                'isbn' => '9780393317558',
                'price' => 16.49,
                'stock_quantity' => 22,
                'description' => 'An exploration of why some societies have historically dominated others.'
            ],
            [
                'category_id' => $categories['History'],
                'title' => 'The Diary of Anne Frank',
                'author' => 'Anne Frank',
                'isbn' => '9780394895982',
                'price' => 9.99,
                'stock_quantity' => 30,
                'description' => 'The original diary of Anne Frank, providing a firsthand account of life in hiding during WWII.'
            ],
            [
                'category_id' => $categories['History'],
                'title' => '1776',
                'author' => 'David McCullough',
                'isbn' => '9780743226721',
                'price' => 17.99,
                'stock_quantity' => 15,
                'description' => 'A detailed account of the pivotal year in American history.'
            ],
            [
                'category_id' => $categories['History'],
                'title' => 'The Wright Brothers',
                'author' => 'David McCullough',
                'isbn' => '9781476728742',
                'price' => 16.99,
                'stock_quantity' => 18,
                'description' => 'The story of the brothers who achieved the first powered flight.'
            ],

            // Self-Help
            [
                'category_id' => $categories['Self-Help'],
                'title' => 'Atomic Habits: An Easy & Proven Way to Build Good Habits & Break Bad Ones',
                'author' => 'James Clear',
                'isbn' => '9780735211292',
                'price' => 15.99,
                'stock_quantity' => 40,
                'description' => 'A practical guide to building good habits and breaking bad ones.'
            ],
            [
                'category_id' => $categories['Self-Help'],
                'title' => 'The 7 Habits of Highly Effective People',
                'author' => 'Stephen R. Covey',
                'isbn' => '9780743269744',
                'price' => 14.99,
                'stock_quantity' => 25,
                'description' => 'Timeless principles for personal and professional effectiveness.'
            ],
            [
                'category_id' => $categories['Self-Help'],
                'title' => 'How to Win Friends and Influence People',
                'author' => 'Dale Carnegie',
                'isbn' => '9780671027032',
                'price' => 12.99,
                'stock_quantity' => 30,
                'description' => 'Classic advice on building relationships and influencing others.'
            ],
            [
                'category_id' => $categories['Self-Help'],
                'title' => 'The Subtle Art of Not Giving a F*ck',
                'author' => 'Mark Manson',
                'isbn' => '9780062457714',
                'price' => 13.99,
                'stock_quantity' => 35,
                'description' => 'A counterintuitive approach to living a good life by focusing on what truly matters.'
            ],
            [
                'category_id' => $categories['Self-Help'],
                'title' => 'Mindset: The New Psychology of Success',
                'author' => 'Carol S. Dweck',
                'isbn' => '9780345472328',
                'price' => 14.49,
                'stock_quantity' => 20,
                'description' => 'How changing your mindset can transform your life and achievements.'
            ],

            // Technology
            [
                'category_id' => $categories['Technology'],
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'price' => 45.99,
                'stock_quantity' => 15,
                'description' => 'A guide to writing clean, maintainable, and efficient code.'
            ],
            [
                'category_id' => $categories['Technology'],
                'title' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author' => 'David Thomas, Andrew Hunt',
                'isbn' => '9780135957059',
                'price' => 49.99,
                'stock_quantity' => 18,
                'description' => 'Timeless techniques and tips for software developers.'
            ],
            [
                'category_id' => $categories['Technology'],
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'isbn' => '9780201633610',
                'price' => 59.99,
                'stock_quantity' => 12,
                'description' => 'Classic guide to software design patterns for object-oriented programming.'
            ],
            [
                'category_id' => $categories['Technology'],
                'title' => 'You Don\'t Know JS: Up & Going',
                'author' => 'Kyle Simpson',
                'isbn' => '9781491904156',
                'price' => 29.99,
                'stock_quantity' => 20,
                'description' => 'Introduction to JavaScript programming concepts.'
            ],
            [
                'category_id' => $categories['Technology'],
                'title' => 'Python Crash Course, 2nd Edition',
                'author' => 'Eric Matthes',
                'isbn' => '9781593279288',
                'price' => 34.99,
                'stock_quantity' => 25,
                'description' => 'A hands-on, project-based introduction to programming with Python.'
            ],

            // Children's Books
            [
                'category_id' => $categories['Children\'s Books'],
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'isbn' => '9780590353427',
                'price' => 12.99,
                'stock_quantity' => 50,
                'description' => 'The magical beginning of Harry Potter\'s journey at Hogwarts School.'
            ],
            [
                'category_id' => $categories['Children\'s Books'],
                'title' => 'The Very Hungry Caterpillar',
                'author' => 'Eric Carle',
                'isbn' => '9780439406537',
                'price' => 7.99,
                'stock_quantity' => 40,
                'description' => 'A classic picture book about a caterpillar\'s journey to becoming a butterfly.'
            ],
            [
                'category_id' => $categories['Children\'s Books'],
                'title' => 'Where the Wild Things Are',
                'author' => 'Maurice Sendak',
                'isbn' => '9780064431992',
                'price' => 8.99,
                'stock_quantity' => 35,
                'description' => 'A beloved story about a boy\'s imaginative journey to an island of wild creatures.'
            ],
            [
                'category_id' => $categories['Children\'s Books'],
                'title' => 'Goodnight Moon',
                'author' => 'Margaret Wise Brown',
                'isbn' => '9780694003617',
                'price' => 6.99,
                'stock_quantity' => 45,
                'description' => 'A gentle bedtime story that has comforted children for generations.'
            ],
            [
                'category_id' => $categories['Children\'s Books'],
                'title' => 'The Cat in the Hat',
                'author' => 'Dr. Seuss',
                'isbn' => '9780375822462',
                'price' => 9.99,
                'stock_quantity' => 40,
                'description' => 'A classic rhyming story about a mischievous cat who visits two children on a rainy day.'
            ],

            // Business & Finance
            [
                'category_id' => $categories['Business & Finance'],
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert T. Kiyosaki',
                'isbn' => '9781612680194',
                'price' => 13.99,
                'stock_quantity' => 30,
                'description' => 'Lessons about money and investing from two fathers with different financial philosophies.'
            ],
            [
                'category_id' => $categories['Business & Finance'],
                'title' => 'The Lean Startup: How Today\'s Entrepreneurs Use Continuous Innovation to Create Radically Successful Businesses',
                'author' => 'Eric Ries',
                'isbn' => '9780307887894',
                'price' => 15.99,
                'stock_quantity' => 25,
                'description' => 'A revolutionary approach to building successful startups.'
            ],
            [
                'category_id' => $categories['Business & Finance'],
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'isbn' => '9780374533557',
                'price' => 16.99,
                'stock_quantity' => 20,
                'description' => 'A groundbreaking exploration of how we think and make decisions.'
            ],
            [
                'category_id' => $categories['Business & Finance'],
                'title' => 'The Intelligent Investor: The Definitive Book on Value Investing',
                'author' => 'Benjamin Graham',
                'isbn' => '9780060555665',
                'price' => 19.99,
                'stock_quantity' => 15,
                'description' => 'The classic guide to value investing and financial markets.'
            ],
            [
                'category_id' => $categories['Business & Finance'],
                'title' => 'Start with Why: How Great Leaders Inspire Everyone to Take Action',
                'author' => 'Simon Sinek',
                'isbn' => '9781591846444',
                'price' => 14.99,
                'stock_quantity' => 22,
                'description' => 'Discover the power of starting with why to inspire others and achieve success.'
            ],

            // Health & Wellness
            [
                'category_id' => $categories['Health & Wellness'],
                'title' => 'The Four Hour Work Week: Escape 9-5, Live Anywhere and Join the New Rich',
                'author' => 'Timothy Ferriss',
                'isbn' => '9780307465356',
                'price' => 15.99,
                'stock_quantity' => 25,
                'description' => 'A guide to lifestyle design and escaping the traditional work paradigm.'
            ],
            [
                'category_id' => $categories['Health & Wellness'],
                'title' => 'The Power of Now: A Guide to Spiritual Enlightenment',
                'author' => 'Eckhart Tolle',
                'isbn' => '9781577314806',
                'price' => 12.99,
                'stock_quantity' => 20,
                'description' => 'A guide to spiritual enlightenment and living in the present moment.'
            ],
            [
                'category_id' => $categories['Health & Wellness'],
                'title' => 'The 4-Hour Body: An Uncommon Guide to Rapid Fat-Loss, Incredible Sex, and Becoming Superhuman',
                'author' => 'Timothy Ferriss',
                'isbn' => '9780307467763',
                'price' => 15.99,
                'stock_quantity' => 20,
                'description' => 'A guide to rapid fat-loss, incredible sex, and becoming superhuman.'
            ],
            [
                'category_id' => $categories['Health & Wellness'],
                'title' => 'The Body Keeps the Score: Brain, Mind, and Body in the Healing of Trauma',
                'author' => 'Bessel van der Kolk',
                'isbn' => '9780143127741',
                'price' => 16.99,
                'stock_quantity' => 18,
                'description' => 'Groundbreaking work on trauma and its effects on the body and mind.'
            ],
            [
                'category_id' => $categories['Health & Wellness'],
                'title' => 'Eat to Live: The Amazing Nutrient-Rich Program for Fast and Sustained Weight Loss',
                'author' => 'Joel Fuhrman',
                'isbn' => '9780316017773',
                'price' => 14.99,
                'stock_quantity' => 22,
                'description' => 'A revolutionary approach to weight loss through nutrient-dense eating.'
            ]
        ];

        foreach ($books as $book) {
            DB::table('books')->insert([
                'category_id' => $book['category_id'],
                'title' => $book['title'],
                'author' => $book['author'],
                'isbn' => $book['isbn'],
                'price' => $book['price'],
                'stock_quantity' => $book['stock_quantity'],
                'description' => $book['description'],
                'cover_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}