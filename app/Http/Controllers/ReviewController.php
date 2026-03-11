
<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has purchased this book
        $hasPurchased = Order::where('user_id', auth()->id())
            ->whereHas('orderItems', function ($query) use ($book) {
                $query->where('book_id', $book->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->route('books.show', $book)
                ->with('error', 'You can only review books you have purchased.');
        }

        $validated['user_id'] = auth()->id();
        $validated['book_id'] = $book->id;

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->first();

        if ($existingReview) {
            $existingReview->update($validated);
            $message = 'Review updated successfully!';
        } else {
            Review::create($validated);
            $message = 'Review submitted successfully!';
        }

        return redirect()->route('books.show', $book)
            ->with('success', $message);
    }

    public function destroy(Review $review)
    {
        // Only allow owner or admin to delete
        if (auth()->id() !== $review->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }
}

