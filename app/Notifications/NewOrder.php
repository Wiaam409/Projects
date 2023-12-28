<?php

namespace App\Notifications;

use http\Env\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\OrderController;
use App\Models\Warehouse;

class NewOrder extends Notification
{
    use Queueable;
    public $order_id = 0, $user_id = 0;

    public function __construct($order_id, $user_id) {
        $this->$order_id = $order_id;
        $this->$user_id = $user_id;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
        ];
    }
}
