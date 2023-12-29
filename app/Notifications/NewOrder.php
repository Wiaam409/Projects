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
    private $order;
    public function __construct($order) {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->order->user_id,
            'order_id' => $this->order['id'] ,
        ];
    }
}
