# laravel-template

## Goal

- Laravel の開発環境を docker で作る
- `docker-compose up -d` したら、初期設計済みの Laravel でコードを書き始められる状態になる

## コンテナ構成

- Nginx (Web サーバ)
- PHP-FPM (PHP の実行環境)
- MySQL (RDBMS)
- phpMyAdmin

## Laravel のバージョン

- 2020/9 時点の LTS である 6.x を選定
- 参考
  - https://laravel.com/docs/master/releases#support-policy

## 設計

- シンプルな設計にする
  - なるべく素の Laravel に近いもの

- 今回はやらない事
  - サービスレイヤの作成
  - Clean Architecture 的なパターン (CQRS など)

### アーキテクチャ (MVC2 vs ADR)

下記の理由で `MVC2` を採用

- `C` を `Single Action Controller` にすれば、実質的に `ADR` と大差がない
    - メソッドを1つのみにする事で「特定のメソッドでのみ必要な依存クラス」の注入が発生しなくなる、というメリットに変わりはない
- `ADR`
    - デメリット
        - Laravel のデフォルトのディレクトリ構成を変える必要があるので、素の Laravel からは離れていく
- `MVC2`
    - メリット
        - 初期学習コストが低そう (公式ドキュメントと親和性高い、情報多い)


### ディレクトリ構成

<details>
<summary>ディレクトリ構成</summary>

```
.
├── src => Laravelプロジェクトをインストールするディレクトリ
├── docker
│   ├── nginx
│   │   ├── Dockerfile
│   │   └── config
│   │       └── default.conf
│   └── php-fpm
│       ├── Dockerfile
│       └── config
│           └── php.ini-development
├── docker-compose.local.yml
├── docker-compose.yml
├── .git
├── .github
│   └── workflows
│       └── laravel.yml => GitHub Actions の設定ファイル
├── .gitignore
├── README.md
└── __memo.md
```
</details>

### ディレクトリ構成 (Laravelプロジェクト)

<details>
<summary>ディレクトリ構成 (Laravelプロジェクト)</summary>

```
./src/
├── app => アプリケーションのコアコード ※別途記載
├── bootstrap
│   ├── app.phpp => フレームワークの初期処理
│   └── cache => RouteやServiceのキャッシュファイル
├── config
│   ├── app.php
│   ├── auth.php => guard の設定等
│   ├── broadcasting.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── view.php
├── database
│   ├── factories
│   │   ├── AdministoratorFactory.php
│   │   ├── GroupFactory.php
│   │   ├── TaskFactory.php
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_resets_table.php
│   │   ├── 2019_08_19_000000_create_failed_jobs_table.php
│   │   ├── 2020_08_31_094414_create_tasks_table.php
│   │   ├── 2020_09_07_065213_change_users_table.php
│   │   ├── 2020_09_08_062142_create_groups_table.php
│   │   └── 2020_09_08_064327_create_administrators_table.php
│   └── seeds
│       └── DatabaseSeeder.php
├── public
│   ├── .htaccess => Web Server が Nginx なので未使用
│   ├── index.php => 全リクエストの入り口。オートローディングを設定。
│   ├── css => Laravel Mix(=webpackのwrapper)によってバンドルされたassets
│   └── js => Laravel Mix によってバンドルされたassets
├── resources
│   ├── js => assets の元ファイル
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── lan => assets の元ファイルg
│   │   └── en
│   ├── sas => assets の元ファイルs
│   │   ├── _variables.scss
│   │   └── app.scss
│   └── views => Blade ファイル(=View Template)群
│       ├── admin => 管理サイト用のBlade
│       ├── common
│       ├── layouts
│       ├── user => ユーザー向けサイト用のBlade
│       └── welcome.blade.php
├── routes
│   ├── admin.php => 管理サイト用のRouteを定義。admin middleware group が適用される。ステートフル。セッションで認証。
│   ├── web.php => ユーザー向けサイト用のRouteを定義。web middleware group が適用される。ステートフル。セッションで認証。
│   ├── api.php => REST API用のRouteを定義。ステートレス。
│   ├── channels.php
│   └── console.php
├── storage
│   ├── app => アプリケーションにより生成されるファイルを保存
│   ├── framework => フレームワークが生成するファイルやキャッシュ
│   │   ├── cache
│   │   ├── sessions
│   │   ├── testing
│   │   └── views
│   ├── logs => ログ
│   │   └── laravel.log
│   ├── oauth-private.key
│   └── oauth-public.key
├── tests => PHPUnit
│   ├── CreatesApplication.php => trait
│   ├── TestCase.php => use CreatesApplication
│   ├── AppTestCase.php => extends TestCase
│   ├── Traits
│   │   └── RoutingTestTrait.php => Routingテスト用のtrait
│   ├── Feature
│   │   ├── ExampleTest.php
│   │   └── app
│   │       └── Http
│   │           └── Controllers
│   │               ├── Admin
│   │               │   ├── Auth
│   │               │   │   └── LoginControllerTest.php => extends AppTestCase
│   │               │   ├── IndexControllerTest.php
│   │               │   └── User
│   │               │       └── UserIndexControllerTest.php
│   │               ├── IndexControllerTest.php
│   │               └── User
│   │                   ├── Auth
│   │                   │   └── LoginControllerTest.php
│   │                   ├── Home
│   │                   │   └── HomeIndexControllerTest.php
│   │                   └── Task
│   │                       ├── TaskDestroyControllerTest.php
│   │                       ├── TaskIndexControllerTest.php
│   │                       └── TaskStoreControllerTest.php
│   └── Unit
│       ├── ExampleTest.php
│       ├── ExampleUseTestingDbTest.php
│       └── app
│           └── Http
│               └── UseCases
│                   ├── Admin
│                   │   └── User
│                   │       └── UserIndexTest.php
│                   └── User
│                       └── Task
│                           ├── TaskDestroyTest.php
│                           ├── TaskIndexTest.php
│                           └── TaskStoreTest.php
├── vendor => compser.jsonによる依存パッケージ群
├── node_modules => packege.jsonによる依存パッケージ群
├── artisan
├── .editorconfig
├── .env
├── .env.example
├── .env.testing
├── .gitattributes
├── .gitignore
├── .styleci.yml
├── README.md
├── __memo.md
├── composer.json
├── package-lock.json
├── package.json
├── phpstan.neon
├── phpunit.xml
├── server.php
├── webpack.mix.js
└── yarn.lock
```
</details>

### ディレクトリ構成 (app以下)

<details>
<summary>ディレクトリ構成 (app以下)</summary>

```
./src/app/
├── Console
│   ├── Commands => カスタム Artisan コマンド(=CLIアプリ) 群
│   │   └── CreateAdminCommand.php
│   └── Kernel.php => CLI向けのKernel
├── Exceptions
│   ├── AppException.php
│   └── Handler.php
├── Http
│   ├── Kernel.php => HTTP Kernel。middleware の設定等。
│   ├── Controllers
│   │   ├── Controller.php => extends Illuminate\Routing\Controller
│   │   ├── IndexController.php
│   │   ├── Admin => 管理サイト用のController群
│   │   │   ├── AdminController.php => extends Controller
│   │   │   ├── IndexController.php => extends AdminController
│   │   │   ├── Auth
│   │   │   │   ├── ConfirmPasswordController.php => extends AdminController
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── ResetPasswordController.php
│   │   │   │   └── VerificationController.php
│   │   │   ├── Home
│   │   │   │   └── HomeIndexController.php
│   │   │   └── User
│   │   │       └── UserIndexController.php
│   │   └── User => ユーザ向けサイト用のController群
│   │       ├── AppController.php => extends Controller
│   │       ├── Auth
│   │       │   ├── ConfirmPasswordController.php => extends Controller
│   │       │   ├── ForgotPasswordController.php
│   │       │   ├── LoginController.php
│   │       │   ├── RegisterController.php
│   │       │   ├── ResetPasswordController.php
│   │       │   └── VerificationController.php
│   │       ├── Home
│   │       │   └── HomeIndexController.php
│   │       └── Task
│   │           ├── TaskDestroyController.php
│   │           ├── TaskIndexController.php
│   │           └── TaskStoreController.php
│   ├── Middleware
│   │   ├── Authenticate.php
│   │   ├── CheckForMaintenanceMode.php
│   │   ├── EncryptCookies.php
│   │   ├── RedirectIfAuthenticated.php
│   │   ├── TrimStrings.php
│   │   ├── TrustProxies.php
│   │   └── VerifyCsrfToken.php
│   ├── Requests => formRequest(=Illuminate\Http\Requestを拡張した機能)群
│   │   ├── Interfaces
│   │   │   └── FormRequestInterface.php
│   │   └── User
│   │       └── Task
│   │           ├── Interfaces
│   │           │   └── TaskStoreRequestInterface.php
│   │           └── TaskStoreRequest.php => バリデーションの設定等。extends Illuminate\Foundation\Http\FormRequest
│   └── UseCases => ビジネスロジック群
│       ├── Admin => 管理サイト用のUseCase
│       │   └── User
│       │       ├── Interfaces
│       │       │   └── UserIndexInterface.php
│       │       └── UserIndex.php
│       └── User => ユーザー向けサイト用のUseCase
│           └── Task
│               ├── Exceptions
│               │   └── TaskDestroyException.php
│               ├── Interfaces
│               │   ├── TaskDestroyInterface.php
│               │   ├── TaskIndexInterface.php
│               │   └── TaskStoreInterface.php
│               ├── TaskDestroy.php
│               ├── TaskIndex.php
│               └── TaskStore.php
├── Models
│   ├── Constants
│   │   ├── TaskConstants.php
│   │   └── UserConstants.php
│   ├── Eloquents
│   │   ├── Eloquent.php
│   │   ├── Authenticatable.php => Auth ファサードの認証機能を利用するには、Authenticatable を継承した EloquentModel が必要
│   │   ├── User.php => user guard で使用するModel。extends Authenticatable implements UserInterface
│   │   ├── Administrator.php => admin guard で使用するModel。extends Authenticatable implements AdministratorInterface
│   │   ├── Group.php => extends Eloquent implements GroupInterface
│   │   └── Task.php => extends Eloquent implements GroupInterface
│   └── Interfaces
│       ├── AdministratorInterface.php
│       ├── BaseInterface.php
│       ├── GroupInterface.php
│       ├── TaskInterface.php
│       └── UserInterface.php
├── Policies => 認可ポリシー群。 `php artisan make:policy` で作成
│   └── TaskPolicy.php
├── Providers => サービスプロバイダー群
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── BroadcastServiceProvider.php
│   ├── EventServiceProvider.php
│   ├── ModelsServiceProvider.php
│   ├── RequestsServiceProvider.php
│   ├── RouteServiceProvider.php
│   └── UseCasesServiceProvider.php
├── Rules => カスタムバリデーションルール群
├── SocialiteProviders => Socialiteで独自の認可サーバを使用する為のカスタムOAuthプロバイダー群
│   └── MyOAuthProvider.php
└── Helpers => カスタムヘルパ群
    └── SortUriHelper.php
```

```
// 下記は必要に応じて追加
./src/app/
├── Event => イベント群。`php artisan make:event` で作成
├── Listeners => イベントリスナ群。`php artisan make:listener` で作成
├── Jobs => Queue 投入可能な Job 群。`php artisan make:job` で作成
├── Mail => `php artisan make:mail` で作成
├── Notifications => `php artisan make:notification` で作成
└── Broadcasting => WebSocket接続経由でのイベントの Broadcast(=サーバサイド/クライアントサイドのJavaScriptで同じ名前のイベントを共有) `php artisan make:channel` で作成

// シンプルなパターンでは使用しない
./src/app/
├── Repositories => リポジトリパターン採用時に使用
└── Services
    ├── XxxService => 各種サービスレイヤ実装時に使用
    ├── Commands => CQRS パターン採用時に使用
    └── Queries => CQRS パターン採用時に使用
```
</details>

### DI と サービスコンテナ

- Model, UseCase, FormRequest 等を追加した際は、`src/app/Providers` 以下のサービスプロバイダーへの登録が必要です
- サービスプロバイダーにインターフェースとクラスを登録する事で、コンストラクタインジェクション/メソッドインジェクションが可能になります

```
// 例: UseCasesServiceProvider に TaskStoreUseCase のインターフェースとクラスを登録
// app/Providers/UseCasesServiceProvider.php

use Illuminate\Support\ServiceProvider;

final class UseCasesServiceProvider extends ServiceProvider
{
    public function register()
    {
        // サービスコンテナにインターフェースとクラスを登録する事で、コンストラクタインジェクション/メソッドインジェクションが可能になる
        $this->app->bind(
            \App\Http\UseCases\User\Task\Interfaces\TaskStoreInterface::class,
            \App\Http\UseCases\User\Task\TaskStore::class
        );
    }
}
```

```
// 例: TaskStoreUseCase をメソッドインジェクション
// app/Http/Controllers/User/Task/TaskStoreController.php

class TaskStoreController extends AppController
{
    public function __invoke(
        Guard $guard,
        TaskStoreRequestInterface $request,
        TaskStoreInterface $useCase // 引数でタイプヒントし、必要なクラスのインターフェースを指定すると、サービスコンテナにより、依存が注入される
    ): RedirectResponse {
    
        // \App\Http\UseCases\User\Task\TaskIndex が注入されている
        $useCase($userId, $request->validated());
        
        // 〜 略 〜
    }
}
```

### DI の活用

DI を活用し、依存性逆転の原則(DIP)に則って実装を行います

```
// 依存の方向

EloquentModel => ORM
↑
UseCase => ビジネスロジック
↑
Controller
```

## 構築方法

### コンテナの起動

```
// リポジトリのclone
$ git clone git@github.com:tamurayk/laravel-template.git
$ cd laravel-template

//※初回起動時は後述の (初回のみ) の作業を行う

// 開発環境の起動
$ docker-compose -f docker-compose.yml up -d

// ※下記のように開発環境を起動すると http://localhost:8080/ で phpMyAdmin にアクセスできます
$ docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
```

### 前準備 (初回のみ)

```
// データベースのデータ保存用ボリュームを作成
$ docker volume create --name laravel-template-database-data

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

```
// test用の Application Key の作成
$ docker exec -it php-fpm /bin/ash
# php artisan key:generate --env=testing
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
        - `Application name` : `laravel-template` ※適宜でok
        - `Homepage URL` : `http://localhost:8000`
        - `Application description` : 任意
        - `Authorization callback URL` : `http://localhost:8000/login/github/callback`
    - `Client secret` を生成
        
- `.env` の下記の項目に上記で作成した `OAuth App` の `Client ID` と `Client secret` を設定

```
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

// See: laravel-template/src/app/SocialiteProviders/MyOAuthProvider.php
MY_OAUTH_PROVIDER_CLIENT_ID=
MY_OAUTH_PROVIDER_CLIENT_SECRET=
```

### アプリケーションへのアクセス

- ユーザー向けサイト: http://localhost:8000/
- 管理サイト: http://localhost:8000/admin
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

## note

### Laravel 再インストール

```
// Laravel 再インストール
$ docker exec -it php-fpm /bin/ash
# rm -rf /srv/* /srv/.*
// 6.x の最新版をインストール
# composer create-project laravel/laravel /srv --prefer-dist "^6.0"

$ docker volume rm laravel-template-database-data
$ docker volume create --name laravel-template-database-data

$ cp examples/laravel-basic-task-list/.env.example src/.env.example
$ cp src/.env.example src/.env
$ docker exec php-fpm php artisan key:generate
$ docker exec php-fpm php artisan migrate
```

### model について

- Laravel のデフォルトでは意図的に `models` ディレクトリが用意されない
  - Eloquent モデルの格納場所は、開発者自身で選択する、という思想
  - デフォルトでは `app` ディレクトリ下へ設置される
- ドキュメント
  - https://readouble.com/laravel/7.x/en/structure.html#introduction


## リクエストライフサイクル

<details>
<summary>ディレクトリ構成</summary>

https://readouble.com/laravel/6.x/ja/lifecycle.html

```
public/index.php
↓
public/index.php:24
  // Composerが生成したオートローダーの定義をロード
  require __DIR__.'/../vendor/autoload.php';
↓
public/index.php:38
  // サービスコンテナ(=DIコンテナ)を生成
  //   ※$app = サービスコンテナ
  $app = require_once __DIR__.'/../bootstrap/app.php';
    ↓
    bootstrap/app.php 
      Illuminate\Foundation\Application を new する
      ↓
      $app->singleton()
        ↓
        \Illuminate\Container\Container::singleton
          サービスコンテナに下記をシングルトンで結合(bind)
          ※結合(bind) = キーと解決処理をペアでサービスコンテナに登録する事
            App\Http\Kernel
            App\Console\Kernel
            App\Exceptions\Handler
      ↓
      // Illuminate\Foundation\Application が return される
      //   Illuminate\Foundation\Application は \Illuminate\Container\Container を継承したものである
      //    つまり、$app = サービスコンテナ である
      return $app;
↓
カーネルクラスのインスタンスを生成
public/index.php:52
  // サービスコンテナが HTTP カーネル を明示的に解決(resolve)
  //  ※解決(resolve) = 結合されたインスタンス化の方法を元に、インスタンスを生成する事
  //   ※makeメソッド = 明示的な解決(resolve)を行う
  $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    ↓
    \Illuminate\Foundation\Application::make
      ↓
      \Illuminate\Container\Container::make
        ↓
        \Illuminate\Container\Container::resolve
          ↓
          与えられたクラス名からインスタンスを生成
          (resolveDependencies() に コンストラクタの引数を配列で渡す)
          \Illuminate\Container\Container::build
            ↓
            resolveDependencies がインスタンスを返す
            \Illuminate\Container\Container::resolveDependencies

↓
public/index.php:54
  // HTTPカーネルによって、リクエストがハンドリングされ、レスポンスを取得
  //  ※$kernel->handle() に渡すRequestの生成に Symfony が使用されてる
  $kernel->handle(
      $request = Illuminate\Http\Request::capture() => \Symfony\Component\HttpFoundation\Request::createFromGlobals
  )
  ↓
  vendor/laravel/framework/src/Illuminate/Foundation/Application.php:902 Application@handle
    ↓
    vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:105 Kernel@handle()
      ↓
      vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:110
      $this->sendRequestThroughRouter($request);
        ↓
        リクエストをミドルウェア/ルーター経由で送信
        \Illuminate\Foundation\Http\Kernel::sendRequestThroughRouter
          ↓
          // リクエストを結合(=bind =キーと解決処理をペアでサービスコンテナに登録)
          $this->app->instance('request', $request);
          ↓
          // 解決済みの Request ファサードをクリア
          Facade::clearResolvedInstance('request');
          ↓
          // bootstrap が実行済みか確認し、未実行であればbootstrapを実行
          \Illuminate\Foundation\Http\Kernel::bootstrap
          ↓
            bootstrap 処理
            \Illuminate\Foundation\Application::bootstrapWith に \Illuminate\Foundation\Http\Kernel::$bootstrappers を渡す
              // 環境変数をロード
              // Config をロード
              // 例外のハンドリング
              // ファサードを登録 (config/app.php の aliases を参照してる)
              // サービスプロバイダーの登録 (config/app.php の providers で設定されたサービスプロバイダーのインスタンス生成)
              // サービスプロバイダーの boot() メソッドを実行
              protected $bootstrappers = [
                  \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
                  \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
                  \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
                  \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
                  \Illuminate\Foundation\Bootstrap\RegisterProviders::class, => \Illuminate\Foundation\Application::registerConfiguredProviders
                  \Illuminate\Foundation\Bootstrap\BootProviders::class, => \Illuminate\Foundation\Application::boot
              ];
          ↓
          // パイプライン生成
          //   ミドルウェアを経由して、Requesut を Router にディスパッチして、Response を取得
          new Pipeline($this->app)
            ↓
            \Illuminate\Routing\Pipeline
            ↓
            \Illuminate\Pipeline\Pipeline::__construct
            ↓
            \Illuminate\Pipeline\Pipeline::send
            ↓
            //ミドルウェアを経由する
            \Illuminate\Pipeline\Pipeline::through
            ↓
            Router に Request をディスパッチ
            \Illuminate\Foundation\Http\Kernel::dispatchToRouter
              ↓
              \Illuminate\Routing\Router::dispatch
              ↓
              \Illuminate\Routing\Router::dispatchToRoute
              ↓
              \Illuminate\Routing\Router::findRoute
              ↓
              \Illuminate\Routing\Router::runRoute
              ↓
              〜 略 〜
              ↓
              // Response インスタンスが返る
              \Illuminate\Routing\Router::prepareResponse
      ↓
      イベントを発火して、イベントリスナをcall
      vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:121
        \Illuminate\Events\Dispatcher
          ↓
          \Illuminate\Events\Dispatcher::dispatch
      ↓
      レスポンスを返す
      vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:125
        return $response;
↓
public/index.php:58
  // HTTP ヘッダーとコンテンツを返す
  $response->send();
    ↓
    \Symfony\Component\HttpFoundation\Response::send
↓
public/index.php:60
  // ターミネート
  $kernel->terminate($request, $response);
    ↓
    \Illuminate\Foundation\Http\Kernel::terminate
      ↓
      \Illuminate\Foundation\Http\Kernel::terminateMiddleware
      ↓
      \Illuminate\Foundation\Application::terminate
```
</details>

### routes/web.php

webミドルウェアグループにアサインされる
app/Providers/RouteServiceProvider.php:59

### `php artisan ui`

- Controller は作成しない
  - `app/Http/Controllers/Auth` は Laravel インストール時に作成されるもの

- `php artisan ui vue --auth` で作成されるものは以下
  - `resources/sass/*`
  - `resources/views/auth/*`
  - `resources/views/layouts/app.blade.php`

- `php artisan ui vue --auth` で変更されるものは以下
  - `package.json `
  - `routes/web.php`
    - `Auth::routes();` が追加される

- 参考コミット
  - https://github.com/tamurayk/laravel-template/commit/be709bb58e0b7e77623936e4303bb8eb0a8537bb#diff-779f18bddd18edad64537505c9b5bcbf

### `Illuminate\Pagination\Paginator` vs `Illuminate\Pagination\LengthAwarePaginator`

- paginator の取得方法
  - `Illuminate\Pagination\Paginator`
    - `\Illuminate\Database\Eloquent\Builder` の `simplePaginate()` メソッドで取得
      - 例
        - `DB::table('users')->simplePaginate()`
        - `$UserEloquentModel->simplePaginate()`
        - `$UserEloquentModel->newQuery()->get()->simplePaginate()`
  
  - `Illuminate\Pagination\LengthAwarePaginator` 
    - `\Illuminate\Database\Eloquent\Builder` の `paginate()` メソッドで取得
        - `DB::table('users')->paginate()`
        - `$UserEloquentModel->paginate()`
        - `$UserEloquentModel->newQuery()->where()->paginate()`
        - note
          - `get()` の後で `->paginate()` すると、`Method Illuminate\Database\Eloquent\Collection::paginate does not exist.` エラーになる

- 現在のページ
  - `paginate()` メソッド実行時に、現在のページ数を、HTTP Request の page クエリ文字列から自動的に取得する
    - SQL に `limit` と `offset` が付与される
