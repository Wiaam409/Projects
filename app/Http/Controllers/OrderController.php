<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function makeOrder(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::id();
        foreach ($input as $item) {
            $validator = Validator::make($item, [
                'scientificName' => 'required',
                'quantity' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'invalid information']);
            }


            Order::create([
                'user_id' => $user_id,
                'scientificName' => $item['scientificName'],
                'quantity' => $item['quantity']
            ]);
        }
        return response()->json(['message' => 'Order has been sent successfully']);
    }
}
