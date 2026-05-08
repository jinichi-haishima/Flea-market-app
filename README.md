#フリマアプリ

##概要
    １０代〜３０代に向けた出品と購入が出来るフリマアプリ

##環境構築
・git clone??
・cd flea-market-app
・docker run --rm \ -u "$(id -u):$(id -g)" \ -v "$(pwd):/var/www/html" \ -w /var/www/html \ laravelsail/php82-composer:latest \ composer install
・cp .env.example .env
    ※M1/M2/M3 Mac（Apple Silicon）をお使いの方
    Composeymlを開きmysqlサービスに platform: 'linux/amd64'を追加してください
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

##開発環境
    お問い合わせ画面：http://localhost/
    ユーザー登録：http://localhost/register
    ログイン画面：http://localhost/login
    管理画面：http://localhost/admin
    phpmyadmin：http://localhost:8080/

##使用技術（実行環境）
    PHP:8.2-fpm
    Laravel:10.x
    Docker / Laravel Sail
    MySQL:8.0
    PhpMyadmin
    Laravel Fortify