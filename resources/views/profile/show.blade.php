@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.show.css') }}">
@endsection

@section('content')
    <div class="profile-container">
        <div class="profile-content">
            <div class="profile-image">
                @if($user?->profile_image_url)
                    <img src="{{ asset('storage/' . $user->profile_image_url) }}" class="profile-avatar">
                @else
                    <img src="{{ asset('img/default-avatar.png') }}" class="profile-avatar">
                @endif
            </div>
            <p class="profile-name"><strong> {{ auth()->user()?->name ?? 'ゲスト' }}</strong></p>
                <div class="profile-links">
                    <a href="{{ route('profile.edit') }}" class="profile-edit-link">プロフィールを編集</a>
                </div>
        </div>
        <div class="item-history">
            <a href="/mypage?page=sell" class="tab-link {{ $currentPage == 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="/mypage?page=buy" class="tab-link {{ $currentPage == 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
        <div class="items-grid">
            <!-- ⭕ 1. いいねした商品の一覧を表示 -->
            @if($currentPage == 'like')
                @foreach($favItems as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', $item->id) }}" class="item-card-link">
                        @if (str_starts_with($item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $item->image_url) }}" class="item-card-image">
                        @else
                            <img src="{{ asset($item->image_url) }}" class="item-card-image">
                        @endif
                        <h3 class="item-card-title">{{ $item->name }}</h3>
                    </a>
                </div>
                @endforeach
            <!-- ⭕ 2. 出品した商品の一覧を表示 -->
            @elseif($currentPage == 'sell')
                @foreach($items as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', $item->id) }}" class="item-card-link">
                        @if (str_starts_with($item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $item->image_url) }}" class="item-card-image">
                        @else
                            <img src="{{ asset($item->image_url) }}" class="item-card-image">
                        @endif
                        <h3 class="item-card-title">{{ $item->name }}</h3>
                    </a>
                </div>
                @endforeach
            <!-- ⭕ 3. 購入した商品の一覧を表示 -->
            @elseif($currentPage == 'buy')
                @foreach($orders as $order)
                <div class="item-card">
                    <a href="{{ route('items.show', $order->item->id) }}" class="item-card-link">
                        @if (str_starts_with($order->item->image_url, 'item_images/'))
                            <img src="{{ asset('storage/' . $order->item->image_url) }}" class="item-card-image">
                        @else
                            <img src="{{ asset($order->item->image_url) }}" class="item-card-image">
                        @endif
                        <h3 class="item-card-title">{{ $order->item->name }}</h3>
                    </a>
                </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection