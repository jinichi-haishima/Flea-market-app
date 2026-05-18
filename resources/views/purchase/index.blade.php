@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase-container">
        <div class="item-info">
            <div class="item-overview">
                <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}" class="item-image">
                <div class="item-details">
                    <h2 class="item-name">{{ $item->name }}</h2>
                    <p class="item-price">価格: ¥{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="payment-method">
                <h2 class="payment-header">支払方法</h2>
                <select name="payment_selection" class="payment-select">
                    <option value="">選択してください</option>
                    <option value="konbini">コンビニ支払い</option>
                    <option value="card">カード支払い</option>
                </select>
            </div>
            <div class="shipping-info">
                <div class="shipping-header">
                    <h2>配送先</h2>
                    <a href="{{ route('shipping_address', $item->id) }}">変更する</a>
                </div>
                <div class="shipping-details">
                    <p class="shipping-postal-code">〒{{ auth()->user()->postal_code }}</p>
                    <p class="shipping-address">{{ auth()->user()->address }}{{ auth()->user()->building ? ' ' . auth()->user()->building : '' }}</p>
                </div>
            </div>
        </div>
        <div class="purchase-summary">
            <table class="purchase-table">
                <tr class="purchase-row">
                    <th>価格</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr class="purchase-row">
                    <th>支払方法</th>
                    <td>
                        <div id="checkout-summary">
                        <p><strong id="selected-method-display">未選択</strong></p>
                        </div>
                    </td>
                </tr>
            </table>
            <form action="{{ route('purchase.store', $item->id) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_selection" id="payment_hidden" value="">
                <input type="hidden" name="shipping_address" value="{{auth()->user()->shipping_address}}">
                <button type="submit" class="btn-purchase">購入を確定する</button>
            </form>
    </div>
@endsection

@push('scripts')
<!-- 購入手続きのJavaScriptコード -->
<script>
    document.addEventListener('DOMContentLoaded', function()
    {
        const paymentSelect = document.querySelector('select[name="payment_selection"]');
        const selectedMethodDisplay = document.getElementById('selected-method-display');
        const paymentHidden = document.getElementById('payment_hidden');

        if (paymentSelect && selectedMethodDisplay) {
            paymentSelect.addEventListener('change', function()
            {
                if (this.value) {

                    const selectedOptionText = this.options[this.selectedIndex].text;
                    selectedMethodDisplay.textContent = selectedOptionText;

                    if (paymentHidden) {
                        paymentHidden.value = this.value;
                    }
                } else {
                    selectedMethodDisplay.textContent = '未選択';
                    if (paymentHidden) {
                        paymentHidden.value = '';
                    }
                }
            });
        }
    });
</script>
@endpush