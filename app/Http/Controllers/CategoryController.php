<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\drugs;

class CategoryController extends Controller
{
    public function showCategories($id)
    {
        $category = Categories::findorfail($id)->drugs;
        return response()->json(['data' => count($category)]);
    }

    public function search(Request $request)
    {
        $search_category = Categories::where('name', $request->name)->first();
        if ($search_category) {
            return response()->json(['data' => $search_category->drugs]);
        }
        // return response()->json(['data'=>'null']);
        $search_name = Drugs::where('scientificName', $request->scientificName)->first();
        if ($search_name) {
            return response()->json(['data' => $search_name]);
        }
        return response()->json(['error' => 'It is empty']);
    }
}
