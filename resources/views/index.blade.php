@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="index-container">
        <nav class="index-navbar">
            <a href="{{ route('users.index') }}" class="nav-link">おすすめ</a>
            <a href="{{ route('profile.show') }}" class="nav-link">マイリスト</a>
        </nav>
        <main class="index-main">
            <div class="items-grid">
                @foreach ($items as $item)
                <div class="item-card-container">
                    <a href="{{ route('items.show', $item->id) }}">
                        <div class="item-card">
                            @if ($item->order)
                                <div class="sold-out-overlay">Sold </div>
                            @endif
                            @if (str_starts_with($item->image_url, 'item_images/'))
                                <img src="{{ asset('storage/' . $item->image_url) }}">
                            @else
                                <img src="{{ asset($item->image_url) }}">
                            @endif
                            <h2 class="item-name">{{ $item->name }}</h2>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </main>
    </div>
@endsection