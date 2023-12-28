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
        $user = User::find(Auth::id());
        if($user->notificatoins == null){
            return response()->json(['Notifications' => 'Empty']);
        }
        return response()->json(['Notifications' => $user->notificatoins]);
    }
}
