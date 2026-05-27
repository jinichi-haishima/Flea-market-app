@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.edit.css') }}">
@endsection

@section('content')
    <div class="edit-profile-container">
        <h2 class="edit-profile-title">プロフィール設定</h2>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-profile-image">
                <div class="profile-image-preview">
                    <img id="image-preview"
                    src="{{ asset('storage/' . $user->profile_image_url) }}" alt="">
                </div>
                <div>
                    <label for="profile_image" class="custom-file-button">画像を選択する</label>
                    <input id="profile_image" type="file" name="profile_image_url" class="hidden-file-input" value="{{ old('profile_image_url', $user->profile_image_url) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
            </div>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            </div>
                @error('postal_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group">
                <label for="address">住所</label>
                <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            </div>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            <div class="form-group">
                <label for="building">建物名</label>
                <input id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
            </div>
            <div class="form-group">
                <button type="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection

<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(file);
    }
});
</script>