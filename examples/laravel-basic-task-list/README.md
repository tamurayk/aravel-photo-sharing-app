## about

- [Laravel 5.2 Basic Task List](https://readouble.com/laravel/5.2/en/quickstart.html) を `Laravel 7.x` で実装

## usage

- `docker-compose.yml` の `services.php-fpm.volumes` の値を `./examples/laravel-basic-task-list:/srv` に変更
- database コンテナ用の volume を削除 & 作成

## dir

```
laravel-basic-task-list $ tree -L 2 -I vendor
.
├── README.md
├── app
│   ├── Console
│   ├── Exceptions
│   ├── Http
│   ├── Providers
│   ├── Task.php
│   └── User.php
├── artisan
├── bootstrap
│   ├── app.php
│   └── cache
├── composer.json
├── composer.lock
├── config
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── view.php
├── database
│   ├── factories
│   ├── migrations
│   └── seeds
├── package.json
├── phpunit.xml
├── public
│   ├── favicon.ico
│   ├── index.php
│   ├── robots.txt
│   └── web.config
├── resources
│   ├── js
│   ├── lang
│   ├── sass
│   └── views
├── routes
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── server.php
├── storage
│   ├── app
│   ├── framework
│   └── logs
├── tests
│   ├── CreatesApplication.php
│   ├── Feature
│   ├── TestCase.php
│   └── Unit
└── webpack.mix.js
```
