<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\NewOrder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = [];
        foreach (Auth::user()->unreadNotifications as $notification) {
            $notifications[] = $notification->data;
        }
        return response()->json(['Notifications' => $notifications]);
    }

    public function readNotificationWarehouse($id)
    {
        $ID = DB::table('notifications')
            ->where('type', 'App\Notifications\NewOrder')
            ->where('data->order_id', $id)
            ->pluck('id');
        DB::table('notifications')->where('id', $ID)->update(['read_at' => now()]);
    }

    public function readNotificationUser($id)
    {
        $ID = DB::table('notifications')
            ->where('type', 'App\Notifications\UpdateStatusOrder')
            ->where('data->order_id', $id)
            ->pluck('id');
        DB::table('notifications')->where('id', $ID)->update(['read_at' => now()]);
    }
}
