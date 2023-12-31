<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use http\Env\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use App\Models\Drugs;
use Validator;

class DrugsController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_id' => 'required|exists:App\Models\Categories,id',
            'scientificName' => 'required',
            'tradeName' => 'required',
            'companyName' => 'required',
            'quantity' => 'required',
            'expires_at' => 'required|date',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'uncompleted information']);
        }
        // if the medicine exists in the warehouse so you have to update the value without creating a new medicine
        $exist = Drugs::where('scientificName', $request->scientificName)->first();
        if ($exist != null) {
            $exist['quantity'] += $request->quantity;
            $exist->save();
            return response()->json(['message' => 'Drug stored successflly']);
        }
        Drugs::create($input);
        return response()->json(['message' => 'Drug stored successflly']);
    }

    public function showAllMedicines()
    {
        $medicines = Drugs::all();
        return Response()->json(['medicines' => $medicines]);
    }

    public function showCategories($id)
    {
        $category = Categories::findorfail($id)->drugs;
        if (count($category) == 0)
            return response()->json(['message' => "No such medicine is here"]);
        return response()->json(['data' => $category]);
    }

    public function showDetails($id)
    {
        $drug = Drugs::find($id);
        return Response()->json(['message' => $drug]);
    }

    public function search(Request $request)
    {
        $search_category = Categories::where('name', $request->categoryName)->first();
        if ($search_category) {
            return response()->json(['data' => $search_category->drugs]);
        }
        $search_name = Drugs::where('scientificName', $request->scientificName)->first();
        if ($search_name) {
            return response()->json(['data' => $search_name]);
        }
        return response()->json(['error' => 'It is empty']);
    }

    public function deleteExpires(Request $request)
    {
        $list = Drugs::whereBetween('expires_at', ["1-1-1", now()])->get();
        if (count($list) == 0) {
            return Response()->json(['message' => 'No expired medicines']);
        }
        foreach ($list as $item) {
            $item->delete();
        }
        return response()->json(['data' => $list]);
    }
}

