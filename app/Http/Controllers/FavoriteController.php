<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    public function destroy($item_id)
    {
        Favorite::where('user_id', Auth::id())->where('item_id', $item_id)->delete();

        return back();
    }
}
