<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.index', compact('item'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {

        $item = Item::findOrFail($item_id);
        // 購入処理のロジックをここに追加
        $order = new Order();
        $order->item_id = $item_id;
        $order->buyer_id = auth()->id();
        $order->shipping_address = $request->input('shipping_address', auth()->user()->address);
        $order->payment = $request->input('payment_selection');
        $order->save();

        return redirect()->route('items.index')->with('message', '購入が完了しました');
    }

    public function changeAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('purchase.shipping_address', compact('item', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $request->validate([
            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();

        return redirect()->route('purchase.index', $item_id);
    }
}
