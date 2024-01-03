<?php

namespace App\Http\Controllers;

use App\Models\Drugs;
use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\NewOrder;
use App\Notifications\UpdateStatusOrder;
use Hamcrest\Number\OrderingComparisonTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class OrderController extends Controller
{
    public function makeOrder(Request $request)
    {

        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'scientificName' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid information']);
        }
        // creating the order in data base
        $order_notify = Order::create([
            'user_id' => $user_id,
            'scientificName' => $request->scientificName,
            'quantity' => $request->quantity
        ]);
        $warehouse = Warehouse::find(1);
        $warehouse->notify(new NewOrder($order_notify));
        //Notification::send($warehouse, new NewOrder(2, $user_id));

        return response()->json(['success' => 'Your order has been sent successfully']);
    }

    public function statusOrder()
    {
        // showing orders for user
        $id = Auth::id();
        $order = User::find($id)->orders;
        return response()->json(['data' => $order]);
    }

    public function showOrders()
    {
        $orders = Order::where('status', '!=', 'Request could not be executed')->get();
        return response()->json(['data' => $orders]);
    }

    public function showOrder($id)
    {
        $order = Order::where('id', $id)->get();
        return response()->json(['data' => $order]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if ($request['status'] == 'sent') {
            if ($order['status'] == 'sent' || $order['status'] == 'recieved') {
                return response()->json(['mesage' => 'This order has already been sent']);
            }
            // To check if There are enough medicine
            $data = Drugs::where('scientificName', $order['scientificName'])->first();
            if ($data == null || ($data != null && $data['quantity'] < $order['quantity'])) {
                $order['status'] = 'Request could not be executed';
            } else {
                //There are enough medicine so update the quantity in the ware house
                $data['quantity'] -= $order['quantity'];
                $order['status'] = 'sent';
                $data->save();
            }
            // update the status
        } else if ($request['status'] == 'recieved') {
            if ($order['status'] == 'Request could not be executed')
                return response()->json(['mesage' => 'It is Impossible']);
            if ($order['status'] == 'preparing')
                return response()->json(['mesage' => 'Sent the order first!']);
            $order['status'] = 'recieved';
        }
        // update the status payment
        if ($request['statusPayment'] == 'paid' && $order['status'] != 'Request could not be executed')
            $order['statusPayment'] = 'paid';
        $order->save();
        $user = User::find($order['user_id']);
        $user->notify(new UpdateStatusOrder($order));
        return response()->json(['mesage' => $order]);
    }
}
