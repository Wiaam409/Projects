<?php

namespace App\Http\Controllers;

use App\Models\Drugs;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function makeOrder(Request $request)
    {
        $canNot[] = array();
        $ind = 0;
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
            $data = Drugs::where('scientificName', $item['scientificName'])->first();
            if ($data == null || ($data != null && $data['quantity'] < $item['quantity'])) {
                $canNot[$ind] = $item['scientificName'];
                $ind++;
                continue;
            }
            Order::create([
                'user_id' => $user_id,
                'scientificName' => $item['scientificName'],
                'quantity' => $item['quantity']
            ]);
        }
        if ($ind > 0) {
            return response()->json(['success' => 'Your order has been sent successfully', 'failed' => 'But unfortunately we do not have enough : ', 'which failed : ' => array_values($canNot)]);
        }
        return response()->json(['success' => 'Your order has been sent successfully', 'failed' => 0, 'which failed : ' => array_values($canNot)]);
    }

    public function statusOrder()
    {
        $id = Auth::id();
        $order = User::findorfail($id)->orders;
        return response()->json(['data' => $order]);
    }
}
