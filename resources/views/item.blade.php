@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
    <div class="item-container">
        <div class="item-image">
            @if (str_starts_with($item->image_url, 'item_images/'))
                <img src="{{ asset('storage/' . $item->image_url) }}">
            @else
                <img src="{{ asset($item->image_url) }}">
            @endif
        </div>
        <div class="item-details">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="item-brand">ブランド: {{ $item->brand }}</p>
            <p class="item-price">¥{{ number_format($item->price) }}（税込）</p>
            <div class="like-comment-section">
                <div class="like-section">
                @if ($item->isFavoritedByAuthUser())
                    <form action="/unlike/{{ $item->id }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; cursor: pointer;">
                            <img src="{{ asset('img/heart-pink.png') }}" width="30" alt="いいね後">
                        </button>
                    </form>
                @else
                    <form action="/like/{{ $item->id }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer;">
                            <img src="{{ asset('img/heart-gray.png') }}" width="30" alt="いいね前">
                        </button>
                    </form>
                @endif
                    <p>{{ $item->favorites->count() }}</p>
                </div>
                <div class="comment-section">
                    <img src="{{ asset('img/comment.png') }}" alt="コメントアイコン">
                    <p>{{ $item->comments->count() }}</p>
                </div>
            </div>
            <div class="purchase-section">
                <a href="{{ route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>
            </div>
            <h2>商品説明</h2>
            <p class="item-description">{{ $item->description }}</p>
            <h2>商品情報</h2>
            <div class="category-row">
                <span class="category-label">カテゴリー</span>
                <div class="category-item">
                    @foreach ($item->categories as $category)
                        <span class="category-name">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="condition-row">
                <span class="category-label">商品の状態</span>
                <div class="condition-list">
                    <span class="condition-name">{{ $item->itemCondition->condition }}</span>
                </div>
            </div>
            <h2>コメント</h2>
            <div class="comment-container">
                <div class="comment-list">
                    @foreach($item->comments as $comment)
                    <div class="comment-item">
                        <div class="comment-user-info">
                            <img src="{{ asset('storage/' . $comment->user->profile_image_url) }}" alt="ユーザープロフィール画像" class="comment-user-image">
                            <p class="comment-user-name"><strong>{{ $comment->user->name }}</strong></p>
                        </div>
                        <p class="comment-content">{{ $comment->content }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <h3>商品へのコメント</h3>
            <div class="comment-form">
                <form action="{{ route('comments.store', $item->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                    <textarea name="content" id="content" rows="5" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">コメントを送信する</button>
                </form>
        </div>
    </div>
@endsection