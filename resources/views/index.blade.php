@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="index-container">
        <nav class="index-navbar">
            <a href="{{ route('users.index', ['tab' => 'recommend']) }}"
            class="tab-link {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('users.index', ['tab' => 'mylist']) }}"
            class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </nav>
        <main class="index-main">
            <div class="items-grid">
                @if($items->isEmpty())
                    <p class="no-items-message">表示する商品がありません。</p>
                @else
                    @foreach ($items as $item)
                    <div class="item-card-container">
                        <a href="{{ route('items.show', $item->id) }}" class="item-card-link">
                            <div class="item-card">
                                @if ($item->order)
                                    <div class="sold-out-overlay">Sold </div>
                                @endif
                                @if (str_starts_with($item->image_url, 'item_images/'))
                                    <img src="{{ asset('storage/' . $item->image_url) }}" class="item-image">
                                @else
                                    <img src="{{ asset($item->image_url) }}" class="item-image">
                                @endif
                                <p class="item-name">{{ $item->name }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @endif
            </div>
        </main>
    </div>
@endsection