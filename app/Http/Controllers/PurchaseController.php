<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->order) {
        return redirect()->route('items.show', $item_id)->with('error', 'この商品はすでに売り切れています。');
    }

    return view('purchase.index', compact('item'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {

        $item = Item::findOrFail($item_id);
        $paymentMethod = $request->input('payment_selection');
        // 購入処理のロジックをここに追加
        $order = new Order();
        $order->item_id = $item_id;
        $order->buyer_id = auth()->id();
        $order->shipping_postal_code = $request->input('postal_code', auth()->user()->postal_code);
        $order->shipping_address = $request->input('address', auth()->user()->address);
        $order->shipping_building = $request->input('building', auth()->user()->building);
        $order->payment = $paymentMethod;
        $order->save();

        if ($paymentMethod === 'card' || $paymentMethod === 'konbini') {
        // StripeのAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

        $stripePaymentTypes = [];
        if ($paymentMethod === 'card') {
            $stripePaymentTypes = ['card'];
        } elseif ($paymentMethod === 'konbini') {
            $stripePaymentTypes = ['konbini'];
        }
        // Stripe Checkoutのセッション（画面）を作成
        $checkoutSession = StripeSession::create([
            'payment_method_types' => $stripePaymentTypes,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // 決済成功時の戻り先URL
            'success_url' => route('items.index', ['success' => 'true']),
            // 決済キャンセル時の戻り先URL（購入画面に戻すなど）
            'cancel_url' => route('items.show', $item_id),
        ]);

            return redirect()->away($checkoutSession->url);
        }
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
