@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shipping_address.css') }}">
@endsection

@section('content')
    <div class="shipping-address-container">
        <h2 class="address-title">住所の変更</h2>
        <form method="POST" action="{{ route('shipping_address', $item->id) }}">
            @csrf
            <div class="form-group">
                <label for="postal_code" class="form-label">郵便番号</label>
                <input id="postal_code" type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code', auth()->user()->postal_code) }}" class="form-input">
                @error('shipping_postal_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">住所</label>
                <input id="address" type="text" name="shipping_address" value="{{ old('shipping_address', auth()->user()->address) }}" class="form-input">
                @error('shipping_address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="building" class="form-label">建物名</label>
                <input id="building" type="text" name="shipping_building" value="{{ old('shipping_building', auth()->user()->building) }}" class="form-input">
            </div>
            <div class="form-group">
                <button type="submit" class="btn-update">更新する</button>
            </div>
        </form>
    </div>
@endsection