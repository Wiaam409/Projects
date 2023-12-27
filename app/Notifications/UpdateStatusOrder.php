<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class UpdateStatusOrder extends Notification
{
    use Queueable;
    private $order_id, $new_status;

    public function __construct($order_id, $new_status)
    {
        $this->$order_id = $order_id;
        $this->$new_status = $new_status;
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
            'new_status' => $this->new_status,
        ];
    }
}
