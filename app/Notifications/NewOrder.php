<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\OrderController;

class NewOrder extends Notification
{
    use Queueable;
    private $order_id, $user_id;

    public function __construct($order_id, $user_id)
    {
        $this->$order_id = $order_id;
        $this->$user_id = $user_id;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
        ];
    }
}
