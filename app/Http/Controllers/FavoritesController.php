<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function addfavorites(){
        if(!auth()->user()->favoritesListHas(request('drug_id'))){
            auth()->user()->favoritesList()->attach(request('drug_id'));
            return response()->json(['massage' => 'add to favorites']);
        }
        return response()->json(['massage' => 'it has already beed added']);
    }

    public function favorites(){
        $drug = auth()->user()->favoritesList()
            ->latest()->get();
        return response()->json(['data' => $drug]);
    }

    public function desroyfavorites(){
        auth()->user()->favoritesList()->detach(request('drug_id'));
        return response()->json(['massage' => 'removed from favorites']);
    }
}
