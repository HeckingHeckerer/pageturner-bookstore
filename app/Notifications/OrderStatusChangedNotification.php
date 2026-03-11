<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusMessage = match ($this->order->status) {
            'processing' => 'Your order is now being processed.',
            'completed' => 'Your order has been completed.',
            'cancelled' => 'Your order has been cancelled.',
            default => 'Your order status has been updated.',
        };

        return (new MailMessage)
            ->subject('Order Status Updated - PageTurner')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($statusMessage)
            ->line('Order ID: #' . $this->order->id)
            ->line('Previous Status: ' . ucfirst($this->oldStatus))
            ->line('New Status: ' . ucfirst($this->order->status))
            ->action('View Order', route('orders.show', $this->order))
            ->line('Thank you for shopping with PageTurner!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order Status Updated',
            'message' => 'Your order #' . $this->order->id . ' status has been updated to ' . $this->order->status,
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'old_status' => $this->oldStatus,
        ];
    }
}

