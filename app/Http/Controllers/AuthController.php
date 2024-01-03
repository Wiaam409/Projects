<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\Warehouse;
use Auth;

class AuthController extends Controller
{
    public function userRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|size:10',
            'password' => 'required|min:8',
        ]);
        $phone = User::where('phone', $request->phone)->first();
        if ($phone != null) {
            return response()->json(['error' => "this phone Number has already been used"], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);
        $token = $user->createToken('drugs', ['user'])->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function warehouseRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|size:10',
            'password' => 'required|min:8',
        ]);
        // check if phoneNumber has been
        $phone = Warehouse::where('phone', $request->phone)->first();
        if ($phone != null) {
            return response()->json(['error' => "this phone Number has already been used"], 200);
        }
        $user = Warehouse::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);
        $token = $user->createToken('drugs', ['warehouse'])->accessToken;
        return response()->json(['token' => $token], 200);
    }


    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('user')->attempt(['phone' => request('phone'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success = $user;
            $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;

            return response()->json(['token' => $success['token']], 200);
        } else {
            return response()->json(['error' => 'phone or Password is not correct.'], 200);
        }
    }

    public function warehouseLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('warehouse')->attempt(['phone' => request('phone'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'warehouse']);

            $warehouse = Warehouse::query()->select('warehouses.*')->find(auth()->guard('warehouse')->user()->id);
            $success = $warehouse;
            $success['token'] = $warehouse->createToken('MyApp', ['warehouse'])->accessToken;

            return response()->json(['token' => $success['token']], 200);
        } else {
            return response()->json(['error' => 'phone or Password is not correct.'], 200);
        }
    }

    public function userLogout()
    {
        Auth::guard('user-api')->user()->token()->revoke();
        return response()->json(['message' => 'success logout']);
    }

    public function warehouseLogout()
    {
        Auth::guard('warehouse-api')->user()->token()->revoke();
        return response()->json(['message' => 'success logout']);
    }
}
