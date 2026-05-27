@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
    <div class="exhibition-container">
        <h1 class="exhibition-title">商品の出品</h1>
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">商品画像</label>
                <div class="image-upload">
                    <label for="image" class="custom-file-button">画像を選択</label>
                    <img src="" id="image-preview" class="image-preview">
                    <input id="image" type="file" name="image" class="hidden-file"value="{{ old('image') }}">
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <h2 class="exhibition-subtitle">商品の詳細</h2>
            <div class="form-group">
                <label class="form-label">カテゴリー</label>
                <div class="category-checkboxes">
                    @foreach($categories as $category)
                        <label class="category-label">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="hidden-checkbox"{{ (is_array(old('category_id')) && in_array($category->id, old('category_id'))) ? 'checked' : '' }}>
                            <span class="category-toggle-button">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">商品の状態</label>
                <select id="condition" name="condition_id" class="form-control">
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->condition }}
                        </option>
                    @endforeach
                </select>
                @error('condition_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <h2 class="exhibition-subtitle">商品名と説明</h2>
            <div class="form-group">
                <label for="name" class="form-label">商品名</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" class="form-input">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="brand" class="form-label">ブランド名</label>
                <input id="brand" type="text" name="brand" value="{{ old('brand') }}" class="form-control" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea id="description" name="description" class="description-text">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="price" class="form-label">販売価格</label>
                <div class="price-input-container">
                    <span class="currency-symbol">¥</span>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" class="form-price-input">
                </div>
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group  submit-button-container">
                <button type="submit" class="submit-button">出品する</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // 商品画像のプレビュー表示
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            // 💡 プレビュー画像をクリックした時もファイル選択がトリガーされるように設定
            imagePreview.addEventListener('click', function() {
                imageInput.click();
            });
        }
    });
</script>
@endpush