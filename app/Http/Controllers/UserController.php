<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Item_condition;
use App\Models\Order;


class UserController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }



    public function mypage()
    {
        $user = auth()->user();
        $currentPage = request()->query('page', 'sell');
        $items = Item::where('seller_id', $user->id)->with('categories', 'itemCondition', 'order')->get();
        $orders = Order::where('buyer_id', $user->id)->with('item')->get();

        return view('profile.show', compact('user', 'items', 'orders', 'currentPage'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        if ($request->hasFile('profile_image_url')) {
            $path = $request->file('profile_image_url')->store('profile_images', 'public');
            $validated['profile_image_url'] = $path;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'プロフィールが更新されました。');
    }

}
