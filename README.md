# laravel-template

## 基本設計

See: https://github.com/tamurayk/laravel-template

## コンテナ構成

- Nginx (Web サーバ)
- PHP-FPM (PHP の実行環境)
- MySQL (RDBMS)
- phpMyAdmin

## 構築方法

### コンテナの起動

```
// リポジトリのclone
$ git clone git@github.com:tamurayk/laravel-photo-sharing-app.git
$ cd laravel-photo-sharing-app

//※初回起動時は後述の (初回のみ) の作業を行う

// 開発環境の起動
$ docker-compose -f docker-compose.yml up -d

// ※下記のように開発環境を起動すると http://localhost:8080/ で phpMyAdmin にアクセスできます
$ docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
```

### 前準備 (初回のみ)

```
// データベースのデータ保存用ボリュームを作成
$ docker volume create --name laravel-photo-sharing-app-database-data

// イメージのビルド
$ docker-compose build

// (Dockerfile を変更した場合は再ビルド)
$ docker-compose build --no-cache
```

```
// test 用DBの作成
$ docker-compose up -d
$ docker exec -it database /bin/bash
# mysql -u root -p
mysql> CREATE DATABASE `webapp_testing`;

// 権限付与
mysql> GRANT ALL ON webapp_testing.* TO webapp;

// (権限確認)
mysql> show grants for 'webapp'@'%';
+------------------------------------------------------------+
| Grants for webapp@%                                        |
+------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'webapp'@'%'                         |
| GRANT ALL PRIVILEGES ON `webapp`.* TO 'webapp'@'%'         |
| GRANT ALL PRIVILEGES ON `webapp_testing`.* TO 'webapp'@'%' |
+------------------------------------------------------------+
```

### 依存パッケージのインストール (初回のみ)

```
$ docker exec -it php-fpm /bin/ash
# composer install
# exit
```

```
$ cd src
$ yarn install
```

### `public/` 以下の assets のバンドル

- 初回起動時、及び、`resource/` 以下のファイルを更新した際は、Laravel Mix の実行が必要です

```
// Laravel Mix(=Webpackのwrapper)で `resource/` 以下のファイルを `public/` 以下にバンドル
$ cd src
$ yarn run dev
```

- 参考
  - https://laravel.com/docs/6.x/frontend#writing-css

### `Application Key` の作成 (初回のみ)

```
$ cp .env.example .env
$ docker exec php-fpm php artisan key:generate
```

```
// test用の Application Key の作成
$ docker exec php-fpm php artisan key:generate --env=testing
```

### migration

```
$ docker exec php-fpm php artisan migrate
```

### seeder の実行

```
$ docker exec -it php-fpm /bin/ash

// (Seederを追加した場合はオートローダを再生成)
# composer dump-autoload

// DatabaseSeeder クラスを実行
# php artisan db:seed
```

### 初期管理ユーザー作成 (初回のみ)

```
# php artisan admin:create
```

### OAuth認証用の設定

- GitHub に OAuth Apps を作成
    - https://github.com/settings/developers より `New OAuth App` を押下
    - 以下のように設定し、`Register application` を押下
        - `Application name` : `laravel-photo-sharing-app` ※適宜でok
        - `Homepage URL` : `http://localhost:8000`
        - `Application description` : 任意
        - `Authorization callback URL` : `http://localhost:8000/login/github/callback`
    - `Client secret` を生成
        
- `.env` の下記の項目に上記で作成した `OAuth App` の `Client ID` と `Client secret` を設定

```
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
```

### アプリケーションへのアクセス

- ユーザー向けサイト: http://localhost:8000/
- ~~管理サイト: http://localhost:8000/admin~~ 現在 未実装
- phpMyAdmin: http://localhost:8080/ (phpMyAdmin コンテナを起動している場合のみアクセス可)

## 静的解析

### PHPStan

- [larastan](https://github.com/nunomaduro/larastan)

```
// run
# ./vendor/bin/phpstan analyse --memory-limit=2G
// help
# ./vendor/bin/phpstan analyse -h
// e.g.
# ./vendor/bin/phpstan analyse -l 6 --memory-limit=2G app/
```

- note
  - 現時点では、Level 6 まで対応した (推奨レベルは要検討)
  - https://phpstan.org/user-guide/rule-levels

### Psalm

- [psalm/psalm-plugin-laravel](https://github.com/psalm/psalm-plugin-laravel)

```
// run
# ./vendor/bin/psalm
```

## PHPUnit

```
// run
# ./vendor/bin/phpunit tests
// help
# ./vendor/bin/phpunit -h
```

## tips

### ルーティング定義の確認

```
php artisan route:list
```
