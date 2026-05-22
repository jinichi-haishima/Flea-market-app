<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
    <title>Flea Market</title>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <nav class="navbar">
                <div class="navbar-left">
                    @if(Request::is('/'))
                        <h1>
                            <a href="{{ url('/') }}">
                                <img src="{{asset('img/COACHTECHヘッダーロゴ.png')}}" alt="ロゴ" class="logo">
                            </a>
                        </h1>
                    @else
                        <div>
                            <a href="{{ url('/') }}">
                                <img src="{{asset('img/COACHTECHヘッダーロゴ.png')}}" alt="ロゴ">
                            </a>
                        </div>
                    @endif
                </div>
                @unless(request()->routeIs('login', 'register', 'verification.notice'))
                <div class="navbar-center">
                    <form action="{{ route('items.index') }}" method="GET" class="search-form">
                        <div class="search-container">
                            <input type="text" name="keyword" value="{{ request('keyword') ?? session('keyword') }}" placeholder="なにをお探しですか？" class="search-input">
                            <button type="submit" class="search-button"></button>
                        </div>
                    </form>
                </div>
                <div class="navbar-right">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-button">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    @endauth
                        <a href="{{ route('profile.show') }}" class="nav-link">マイページ</a>
                        <a href="{{ route('items.create') }}" class="nav-button">出品</a>
                </div>
                @endunless
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
@stack('scripts')
</body>
</html>