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
        $validator = Validator::make($request->all(), [
            'scientificName' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid information']);
        }
        // creating the order in data base
        $order_notify = Order::create([
            'user_id' => Auth::id(),
            'scientificName' => $request->scientificName,
            'quantity' => $request->quantity
        ]);
        $warehouse = Warehouse::find(1);
        $warehouse->notify(new NewOrder($order_notify));
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
        $order = Order::where('id', $id)->first();
        return response()->json(['data' => $order]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if ($request['status'] == 'Sent') {
            if ($order['status'] == 'Sent' || $order['status'] == 'Received') {
                return response()->json(['mesage' => 'This order has already been sent']);
            }
            // To check if There are enough medicine
            $data = Drugs::where('scientificName', $order['scientificName'])->first();
            if ($data == null || ($data != null && $data['quantity'] < $order['quantity'])) {
                $order['status'] = 'Request could not be executed';
            } else {
                //There are enough medicine so update the quantity in the ware house
                $data['quantity'] -= $order['quantity'];
                $order['status'] = 'Sent';
                $data->save();
            }
            // update the status
        } else if ($request['status'] == 'Received') {
            if ($order['status'] == 'Request could not be executed')
                return response()->json(['mesage' => 'It is Impossible']);
            if ($order['status'] == 'Preparing')
                return response()->json(['mesage' => 'Sent the order first!']);
            $order['status'] = 'Received';
        }
        // update the status payment
        if ($request['statusPayment'] == 'Paid' && $order['status'] != 'Request could not be executed')
            $order['statusPayment'] = 'Paid';
        $order->save();
        $user = User::find($order['user_id']);
        $user->notify(new UpdateStatusOrder($order));
        return response()->json(['mesage' => $order]);
    }
}
