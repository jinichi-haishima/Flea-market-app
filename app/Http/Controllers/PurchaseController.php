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
        $order->shipping_postal_code = $request->input('shipping_postal_code', auth()->user()->postal_code);
        $order->shipping_address = $request->input('shipping_address', auth()->user()->address);
        $order->shipping_building = $request->input('shipping_building', auth()->user()->building);
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
            'success_url' => route('items.show', ['id' => $item_id, 'success' => route('checkout.success', ['id' => $item_id])]),
            // 決済キャンセル時の戻り先URL（購入画面に戻すなど）
            'cancel_url' => route('items.show', ['id' => $item_id, 'cancel' => route('checkout.cancel', ['id' => $item_id])]),
        ]);

            return redirect()->away($checkoutSession->url);
        }
    }

    public function stripeSuccess($id)
{
    // ここでデータベースの購入確定処理（ordersテーブルへのインサートなど）を
    // 本来 store メソッドでやっていた場合はここに移植するか、すでに終わっている場合はそのままでOK
    // ➔ 別タブを自動で閉じるJavaScriptを直接返す
    return '
        <script>
            alert("ご購入ありがとうございました！このウィンドウを閉じます。");
            window.close();
        </script>
    ';
}

// 🟢 決済キャンセル時にStripeから呼ばれる処理
public function stripeCancel($id)
{
    // キャンセル時は、新しく開いたタブをそのまま閉じるだけにする
    return '
        <script>
            alert("決済がキャンセルされました。");
            window.close();
        </script>
    ';
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
            'shipping_postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => 'required|string|max:255',
            'shipping_building' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $user->postal_code = $request->input('shipping_postal_code');
        $user->address = $request->input('shipping_address');
        $user->building = $request->input('shipping_building');
        $user->save();

        return redirect()->route('purchase.index', ['item_id' => $item_id]);
    }
}
