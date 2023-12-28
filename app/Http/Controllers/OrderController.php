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

class OrderController extends Controller
{

    public function makeOrder(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::id();
        foreach ($input as $order) {
            $validator = Validator::make($order, [
                'scientificName' => 'required',
                'quantity' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'invalid information']);
            }
            // it's up to you , if you want to update and check the quantity is it enough or not
            // so you decide the status of the order
            /* $data = Drugs::where('scientificName', $order['scientificName'])->first();
            if ($data == null || ($data != null && $data['quantity'] < $order['quantity'])) {
                $canNot[$ind] = $order['scientificName'];
                $ind++;
                continue;
            }
            /*$data['quantity'] -= $order['quantity'];
            $data->save();
            $order['status'] = 'sent';*/
            // creating the order in data base
            Order::create([
                'user_id' => $user_id,
                'scientificName' => $order['scientificName'],
                'quantity' => $order['quantity']
            ]);
            $order_id = count(Order::all());
            $warehouse = Warehouse::find(3);//where('id', 3)->first();
            $warehouse->notify(new NewOrder($order_id, $user_id));
            //Notification::send($warehouse, new NewOrder(2, $user_id));
        }
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
        $orders = Order::where('status', '!=', 'Request could not be executed');
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
        if ($request['statusPayment'] == 'Paid' && $order['status'] != 'Request could not be executed')
            $order['statusPayment'] = 'paid';
        $order->save();
        $user = User::where('id', $order['user_id'])->first();
        Notification::send($user, new UpdateStatusOrder($order['id'], $order['status']));
        return response()->json(['mesage' => $order]);
    }
}
