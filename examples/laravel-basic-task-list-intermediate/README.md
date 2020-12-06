## about

- [Laravel 5.2 > Tutorials > Intermediate Task List](https://laravel.com/docs/5.2/quickstart-intermediate) を `Laravel 7.x` で実装

## usage

- `docker-compose.override.yml` を下記のように設定

```yml
version: '3'

services:
  php-fpm:
    volumes:
      - ./examples/laravel-basic-task-list-intermediate:/srv

  nginx:
    volumes:
      - ./examples/laravel-basic-task-list-intermediate/public:/srv/public
```

- database コンテナ用の volume を削除 & 作成

```
$ docker volume rm laravel-template-database-data
$ docker volume create --name laravel-template-database-data
```

- コンテナ起動

```
$ docker-compose -f docker-compose.yml -f docker-compose.override.yml -f docker-compose.local.yml up -d
```

## dir

```
laravel-basic-task-list-intermediate $ tree -L 4 -I "vendor|node_modules"
.
├── README.md
├── app
│   ├── Console
│   │   └── Kernel.php
│   ├── Exceptions
│   │   └── Handler.php
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Auth
│   │   │   ├── Controller.php
│   │   │   ├── HomeController.php
│   │   │   └── TaskController.php
│   │   ├── Kernel.php
│   │   └── Middleware
│   │       ├── Authenticate.php
│   │       ├── CheckForMaintenanceMode.php
│   │       ├── EncryptCookies.php
│   │       ├── RedirectIfAuthenticated.php
│   │       ├── TrimStrings.php
│   │       ├── TrustHosts.php
│   │       ├── TrustProxies.php
│   │       └── VerifyCsrfToken.php
│   ├── Policies
│   │   └── TaskPolicy.php
│   ├── Providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── BroadcastServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Repositories
│   │   └── TaskRepository.php
│   ├── Task.php
│   ├── User.php
│   └── public
├── artisan
├── bootstrap
│   ├── app.php
│   └── cache
│       ├── packages.php
│       └── services.php
├── composer.json
├── composer.lock
├── config
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── cors.php
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
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_resets_table.php
│   │   ├── 2019_08_19_000000_create_failed_jobs_table.php
│   │   └── 2020_08_28_132631_create_tasks_table.php
│   └── seeds
│       └── DatabaseSeeder.php
├── package.json
├── phpunit.xml
├── public
│   ├── css
│   │   └── app.css
│   ├── favicon.ico
│   ├── index.php
│   ├── js
│   │   └── app.js
│   ├── mix-manifest.json
│   ├── robots.txt
│   └── web.config
├── resources
│   ├── js
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   └── components
│   │       └── ExampleComponent.vue
│   ├── lang
│   │   └── en
│   │       ├── auth.php
│   │       ├── pagination.php
│   │       ├── passwords.php
│   │       └── validation.php
│   ├── sass
│   │   ├── _variables.scss
│   │   └── app.scss
│   └── views
│       ├── auth
│       │   ├── login.blade.php
│       │   ├── passwords
│       │   ├── register.blade.php
│       │   └── verify.blade.php
│       ├── common
│       │   └── errors.blade.php
│       ├── home.blade.php
│       ├── layouts
│       │   ├── app.blade.php
│       │   └── auth.blade.php
│       ├── tasks
│       │   └── index.blade.php
│       └── welcome.blade.php
├── routes
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── server.php
├── storage
│   ├── app
│   │   └── public
│   ├── framework
│   │   ├── cache
│   │   │   └── data
│   │   ├── sessions
│   │   │   ├── 90qIjqFqBw6ZwVZxYECICxjtijsaiJw2KPKcLEFA
│   │   │   └── n4e1Zb45U236GP7a3GETDv5p5PnpmhARmig0lio1
│   │   ├── testing
│   │   └── views
│   │       ├── 1af901ac366f395e5063882b9c7db97b8ca10260.php
│   │       ├── 3d973b8ba6fd7e04aed796a6666df15bea5e5c3a.php
│   │       ├── 51b3e3a49ff60ee2466651e905ca448f713ec3cf.php
│   │       ├── 6826df880cedb95bdb49dd73de2759963e0526a1.php
│   │       ├── 698ac2fb7cb77024e4701c7437a4d945ecc77e2f.php
│   │       ├── 78f3ebfd3bce12d869c142630c984bf4f563f768.php
│   │       ├── b9bc6826dc029a3a40add75a628471e7b707ab35.php
│   │       ├── efae899f1a8920c7bff5042bde1e357763c63cfb.php
│   │       ├── f227f31bab9c8d5a6811f59d49d900826eb8de9f.php
│   │       ├── f49a836ff196582db4b605bcdd33433ef6048863.php
│   │       ├── f9353b16a7ec10936506a895c66f122f4eeaf4c5.php
│   │       └── ff7a721dac4d45a298a227ae92220ee4099821e5.php
│   └── logs
│       └── laravel.log
├── tests
│   ├── CreatesApplication.php
│   ├── Feature
│   │   └── ExampleTest.php
│   ├── TestCase.php
│   └── Unit
│       └── ExampleTest.php
├── webpack.mix.js
└── yarn.lock
```
