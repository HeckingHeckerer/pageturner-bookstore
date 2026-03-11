<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Review Submitted - PageTurner')
            ->greeting('Hello Admin!')
            ->line('A new review has been submitted.')
            ->line('Book: ' . $this->review->book->title)
            ->line('Reviewer: ' . $this->review->user->name)
            ->line('Rating: ' . str_repeat('★', $this->review->rating) . str_repeat('☆', 5 - $this->review->rating))
            ->line('Comment: ' . ($this->review->comment ?: 'No comment'))
            ->action('View Reviews', route('books.show', $this->review->book))
            ->line('Please review this submission.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Review Submitted',
            'message' => 'New review for "' . $this->review->book->title . '" by ' . $this->review->user->name,
            'review_id' => $this->review->id,
            'book_id' => $this->review->book_id,
            'rating' => $this->review->rating,
        ];
    }
}

