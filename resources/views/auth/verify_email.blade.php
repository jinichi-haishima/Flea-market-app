@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify_email.css') }}">
@endsection

@section('content')
    <div class="verify-email-container">
        <div class="verify-email-content">
            <p class="verify-email-message">登録していただいたメールアドレスに確認メールを送信しました。</p>
            <p class="verify-email-message">メール認証を完了させてください。</p>
            <div class="verification-link-container">
                <a href="http://localhost:8025" target="_blank" class="btn btn-success" >
            認証はこちらから
            </a>
            </div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    認証メールを再送する
                </button>
                @if (session('status') == 'verification-link-sent')
                    <p class="resent-message">新しい確認メールが送信されました。</p>
                @endif
            </form>
        </div>
    </div>
@endsection