@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
    <div class="item-container">
        <div class="item-image">
            @if (str_starts_with($item->image_url, 'item_images/'))
                <img src="{{ asset('storage/' . $item->image_url) }}" class="item-image-detail">
            @else
                <img src="{{ asset($item->image_url) }}" class="item-image-detail">
            @endif
        </div>
        <div class="item-details">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="item-brand">ブランド名 {{ $item->brand }}</p>
            <div class="item-price-section">
                <h2 class="item-price">¥{{ number_format($item->price) }}</h2>
                <p>（税込）</p>
            </div>
            <div class="like-comment-section">
                <div class="like-section">
                @if ($item->isFavoritedByAuthUser())
                    <form action="/unlike/{{ $item->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-like" >
                            <img src="{{ asset('img/heart-pink.png') }}"  alt="いいね後" class="liked-heart">
                        </button>
                    </form>
                @else
                    <form action="/like/{{ $item->id }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-like">
                            <img src="{{ asset('img/heart-gray.png') }}" alt="いいね前" class="liked-heart">
                        </button>
                    </form>
                @endif
                    <p class="like-count">{{ $item->favorites->count() }}</p>
                </div>
                <div class="comment-section">
                    <img src="{{ asset('img/comment.png') }}" alt="コメントアイコン" class="comment-icon">
                    <p class="comment-icon-count">{{ $item->comments->count() }}</p>
                </div>
            </div>
            <div class="purchase-section">
                @if ($item->order)
                    <button class="btn-base btn-soldout" disabled>売り切れました</button>
                @elseif ($item->seller_id === auth()->id())
                    <button class="btn-base btn-soldout" disabled>自分が 出品した商品です</button>
                @else
                    <a href="{{ route('purchase.index', $item->id) }}" class="btn-base btn-purchase">購入手続きへ</a>
                @endif
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
            <div class="comment-container">
                <div class="comment-header">
                    <h2>コメント</h2>
                    <p class="comment-count">({{ $item->comments->count() }})</p>
                </div>
                <div class="comment-list">
                    @foreach($item->comments as $comment)
                    <div class="comment-item">
                        <div class="comment-user-info">
                            @if ($comment->user->profile_image_url)
                                <img src="{{ asset('storage/' . $comment->user->profile_image_url) }}" alt="ユーザー画像" class="comment-user-image">
                            @else
                                <div class="default-avatar-icon"></div>
                            @endif
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
                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn-primary">コメントを送信する</button>
                </form>
        </div>
    </div>
@endsection