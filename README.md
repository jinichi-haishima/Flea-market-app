# フリマアプリ

## 概要
    １０代〜３０代に向けた出品と購入が出来るフリマアプリ

## 環境構築
```bash
git clone??コードコピーする！！！
cd flea-market-app
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php85-composer:latest \
  composer install
cp .env.example .env
```
    ※M1/M2/M3 Mac（Apple Silicon）をお使いの方
    Compose.yamlを開きmysqlサービスに platform: 'linux/amd64'を追加してください
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

## 開発環境・URL一覧
### 🛒 商品関連
* **商品一覧画面（トップ画面）：** http://localhost/
* **商品一覧画面（マイリスト）：** http://localhost/?tab=mylist （※要ログイン）
* **商品詳細画面：** http://localhost/item/{item_id}

### 🛍️ 購入・出品関連
* **商品購入画面：** http://localhost/purchase/{item_id} （※要ログイン）
* **住所変更ページ：** http://localhost/purchase/address/{item_id} （※要ログイン）
* **商品出品画面：** http://localhost/sell （※要ログイン）

### 👤 ユーザー・マイページ関連
* **会員登録画面：** http://localhost/register
* **ログイン画面：** http://localhost/login
* **プロフィール画面（出品した商品一覧）：** http://localhost/mypage?page=sell （※要ログイン）
* **プロフィール画面（購入した商品一覧）：** http://localhost/mypage?page=buy （※要ログイン）
* **プロフィール編集画面：** http://localhost/mypage/profile （※要ログイン）

### ⚙️ 開発・テスト用ツール
* データベース管理（phpMyAdmin）： http://localhost:8080/
* メール確認ツール（Mailtrap）： [Mailtrapのダッシュボードを開く](https://mailtrap.io/)

---

### 🔑 テスト用ログインアカウント
動作確認の際は、以下のテストユーザー、または新規登録したアカウントをご利用ください。
* **メールアドレス:** test@example.com
* **パスワード:** password

## 使用技術（実行環境）
    PHP: 8.5.3
    Laravel: 10.50.2
    Docker / Laravel Sail
    MySQL: 8.4.9
    phpMyAdmin
    Laravel Fortify
    Stripe
    Mailtrap