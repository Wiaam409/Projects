<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateSignature;
use App\Models\Drugs;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\Models\Order;

class ReportsController extends Controller
{
    public function warehouseReports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);
        if ($validator->fails())
            return response()->json(['data' => $validator->errors()]);
        $orders = Order::where('statusPayment', 'paid')
            ->whereBetween('created_at', [$request['start_date'], $request['end_date']])
            ->get();
        return response()->json(['data' => $orders]);
    }

    public function userReports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);
        if ($validator->fails())
            return response()->json(['data' => $validator->errors()]);
        $orders = Order::where('user_id', Auth::id())
            ->whereBetween('created_at', [$request['start_date'], $request['end_date']])
            ->get();
        return response()->json(['data' => $orders]);
    }
}
