<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order Received - PageTurner')
            ->greeting('Hello Admin!')
            ->line('A new order has been placed.')
            ->line('Order ID: #' . $this->order->id)
            ->line('Customer: ' . $this->order->user->name)
            ->line('Email: ' . $this->order->user->email)
            ->line('Total Amount: $' . number_format($this->order->total_amount, 2))
            ->line('Status: ' . ucfirst($this->order->status))
            ->action('View Order', route('admin.orders.show', $this->order))
            ->line('Please process this order at your earliest convenience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Order Received',
            'message' => 'New order #' . $this->order->id . ' from ' . $this->order->user->name,
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'status' => $this->order->status,
        ];
    }
}

