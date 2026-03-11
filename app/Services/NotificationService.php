<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function createNotification(User $user, string $type, array $data): Notification
    {
        return $user->notifications()->create([
            'type' => $type,
            'data' => $data
        ]);
    }

    /**
     * Create order placed notification for customer
     */
    public function notifyOrderPlaced(User $customer, int $orderId, float $totalAmount): Notification
    {
        return $this->createNotification($customer, 'order_placed', [
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'message' => "Your order #{$orderId} has been placed successfully!"
        ]);
    }

    /**
     * Create order status changed notification for customer
     */
    public function notifyOrderStatusChanged(User $customer, int $orderId, string $status): Notification
    {
        return $this->createNotification($customer, 'order_status_changed', [
            'order_id' => $orderId,
            'status' => $status,
            'message' => "Your order #{$orderId} status has been updated to: {$status}"
        ]);
    }

    /**
     * Create new order notification for administrators
     */
    public function notifyAdminNewOrder(User $admin, int $orderId, float $totalAmount): Notification
    {
        return $this->createNotification($admin, 'new_order', [
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'message' => "New order #{$orderId} has been created with total amount: ₱{$totalAmount}"
        ]);
    }

    /**
     * Create new review notification for administrators
     */
    public function notifyAdminNewReview(User $admin, int $reviewId, string $bookTitle, int $rating): Notification
    {
        return $this->createNotification($admin, 'new_review', [
            'review_id' => $reviewId,
            'book_title' => $bookTitle,
            'rating' => $rating,
            'message' => "New review submitted for '{$bookTitle}' with rating {$rating}/5"
        ]);
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return $user->notifications()->whereNull('read_at')->count();
    }

    /**
     * Get all notifications for a user
     */
    public function getUserNotifications(User $user, int $limit = 20)
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
    }

    /**
     * Get all admin users
     */
    public function getAdminUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', 'admin')->get();
    }
}