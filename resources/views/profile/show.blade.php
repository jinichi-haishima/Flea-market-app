@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.show.css') }}">
@endsection

@section('content')
    <div class="profile-container">
        <div class="profile-content">
            <div class="profile-image">
                <img src="" alt="">
            </div>
            @auth
                <p><strong> {{ auth()->user()->name }}</strong></p>
                <div class="profile-links">
                    <a href="{{ route('profile.edit') }}">プロフィールを編集</a>
                </div>
            @endauth
        </div>
        <div class="item-history">
            <a href="/mypage?page=sell" class="{{ $currentPage == 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="/mypage?page=buy" class="{{ $currentPage == 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
        <div class="items-grid">
            <!-- ⭕ 1. いいねした商品の一覧を表示 -->
            @if($currentPage == 'like')
                @foreach($favItems as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', $item->id) }}">
                        @if (str_starts_with($item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $item->image_url) }}">
                        @else
                            <img src="{{ asset($item->image_url) }}">
                        @endif
                        <h3>{{ $item->name }}</h3>
                    </a>
                </div>
                @endforeach
            <!-- ⭕ 2. 出品した商品の一覧を表示 -->
            @elseif($currentPage == 'sell')
                @foreach($items as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', $item->id) }}">
                        @if (str_starts_with($item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $item->image_url) }}">
                        @else
                            <img src="{{ asset($item->image_url) }}">
                        @endif
                        <h3>{{ $item->name }}</h3>
                    </a>
                </div>
                @endforeach
            <!-- ⭕ 3. 購入した商品の一覧を表示 -->
            @elseif($currentPage == 'buy')
                @foreach($orders as $order)
                <div class="item-card">
                    <a href="{{ route('items.show', $order->item->id) }}">
                        @if (str_starts_with($order->item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $order->item->image_url) }}">
                        @else
                            <img src="{{ asset($order->item->image_url) }}">
                        @endif
                        <h3>{{ $order->item->name }}</h3>
                    </a>
                </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection