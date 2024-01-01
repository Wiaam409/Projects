<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
}
