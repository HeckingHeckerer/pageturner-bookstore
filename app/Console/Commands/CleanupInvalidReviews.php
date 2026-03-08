<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Console\Command;

class CleanupInvalidReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:cleanup-invalid {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove reviews from users who haven\'t purchased the reviewed books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No reviews will be deleted');
        }

        $this->info('Starting review cleanup...');

        // Get all reviews
        $reviews = Review::with(['user', 'book'])->get();
        $totalReviews = $reviews->count();
        
        $this->info("Found {$totalReviews} total reviews");

        $invalidReviews = [];
        $validReviews = 0;

        foreach ($reviews as $review) {
            // Check if user has purchased this book
            $hasPurchased = Order::where('user_id', $review->user_id)
                ->whereHas('orderItems', function ($query) use ($review) {
                    $query->where('book_id', $review->book_id);
                })
                ->exists();

            if (!$hasPurchased) {
                $invalidReviews[] = $review;
            } else {
                $validReviews++;
            }
        }

        $invalidCount = count($invalidReviews);
        
        $this->info("Found {$invalidCount} invalid reviews (users who haven't purchased the book)");
        $this->info("Found {$validReviews} valid reviews");

        if ($invalidCount === 0) {
            $this->info('No invalid reviews found. All reviews are valid!');
            return;
        }

        if ($dryRun) {
            $this->warn('The following reviews would be deleted:');
            foreach ($invalidReviews as $review) {
                $this->line("- Review ID {$review->id}: User '{$review->user->name}' reviewed '{$review->book->title}' (Rating: {$review->rating})");
            }
        } else {
            $this->warn("Deleting {$invalidCount} invalid reviews...");
            
            $deletedCount = 0;
            foreach ($invalidReviews as $review) {
                $review->delete();
                $deletedCount++;
                
                if ($deletedCount % 10 === 0) {
                    $this->info("Deleted {$deletedCount}/{$invalidCount} reviews...");
                }
            }
            
            $this->info("Successfully deleted {$deletedCount} invalid reviews");
        }

        $remainingReviews = $totalReviews - ($dryRun ? 0 : $invalidCount);
        $this->info("Cleanup complete. {$remainingReviews} valid reviews remain in the database.");
    }
}
