@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@push('scripts')
<script>
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');

// 2. ファイルが選択された時（changeイベント）の処理を設定
imageInput.addEventListener('change', function(e) {
    alert('ファイルが選択されました'); // デバッグ用のアラート
    // 選択されたファイルを取得
    const file = e.target.files[0];

    // ファイルが存在する場合のみ処理
    if (file) {
        // FileReaderオブジェクトを作成（ファイルを読み込むための仕組み）
        const reader = new FileReader();

        // ファイルの読み込みが完了した時の処理
        reader.onload = function(e) {
            // imgタグのsrc属性に、読み込んだ画像のデータ（URL）をセット
            imagePreview.src = e.target.result;

            imagePreview.style.display = 'block';
        }
        // ファイルをデータURLとして読み込む
        reader.readAsDataURL(file);
    }
});
imagePreview.addEventListener('click', function() {
    // 隠れているファイル入力（input）を代わりにクリックさせる
    imageInput.click();
});

</script>
@endpush

@section('content')
    <div class="exhibition-container">
        <h1 class="exhibition-title">商品の出品</h1>
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">商品画像</label>
                <div class="image-upload">
                    <label for="image" class="custom-file-button">画像を選択</label>
                    <img src="" alt="" id="image-preview" class="image-preview">
                    <input id="image" type="file" name="image" class="hidden-file" required>
                </div>
            </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <h2>商品の詳細</h2>
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
            </div>
            <div>
                @error('category_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">商品の状態</label>
                <select id="condition" name="condition_id" class="form-control" required>
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->condition }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                @error('condition_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <h2>商品名と説明</h2>
            <div class="form-group">
                <label for="name" class="form-label">商品名</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group">
                <label for="brand" class="form-label">ブランド名</label>
                <input id="brand" type="text" name="brand" value="{{ old('brand') }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea id="description" name="description" class="form-control" required>{{ old('description') }}</textarea>
            </div>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group">
                <label for="price" class="form-label">販売価格</label>
                <div class="price-input-container">
                    <span class="currency-symbol">¥</span>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" class="form-price-input">
                </div>
            </div>
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group  submit-button">
                <button type="submit">出品する</button>
            </div>
        </form>
    </div>
@endsection