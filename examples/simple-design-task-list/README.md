## 作業log

1. volume 再作成

```
$ docker stop `docker ps -a -q` && docker rm `docker ps -a -q`
$ docker volume rm laravel-template-database-data
$ docker volume create laravel-template-database-data
```

1. コンテナ起動

```
$ docker-compose -f docker-compose.yml -f docker-compose.override.yml -f docker-compose.local.yml up -d
```

1. Laravel インストール

```
$ docker exec -it php-fpm /bin/ash
# rm -rf /srv/* /srv/.*
# composer create-project laravel/laravel /srv --prefer-dist "^6.0"
```

1. `.env` 作成

```
cp examples/laravel-basic-task-list-intermediate/.env.example examples/simple-design-task-list/.env.example
cp .env.example .env
php artisan key:generate
```

1. Task テーブル作成

```
php artisan make:migration create_tasks_table --create=tasks
php artisan migrate
```

1. Task Eloquent モデル作成

```
$ mkdir app/Entities/
$ mkdir app/Entities/Constants
$ mkdir app/Entities/Contracts
$ mkdir app/Entities/Eloquents

# php artisan make:model Entities/Eloquents/Task
```

1. 認証 scaffold

```
# composer require laravel/ui "^1.0" --dev
# php artisan ui vue --auth

$ yarn install
$ yarn dev
```

1. TaskController

```
# php artisan make:controller TaskController
```

1. Router

```
```

1. View

```
$ cp -R ../laravel-basic-task-list-intermediate/resources/views/* resources/views/
```

1. 認可ポリシー

```
# php artisan make:policy TaskPolicy
```

1. formRequest

```
# php artisan make:request TaskStoreRequest
```

1. Laravel Debugbar

```
# composer require barryvdh/laravel-debugbar --dev
```

1. Factory

```
# php artisan make:factory TaskFactory --model=Task
```


## 作業log (Admin)

1. Admin/Task/TaskIndexController.php
  - CakePHP の bake と違って空の XxxxController.php が生成されるだけ (testやmodelは生成されない)

```
# php artisan make:controller Admin/User/UserIndexController
```

1. blade
  - view は artisan では作成できないので自分で作る
  
```
$ mkdir -p resources/views/admin/user
$ simple-design-task-list $ touch resources/views/admin/user/index.blade.php
```

1. 初期管理者作成用コマンド

```
# php artisan make:model Models/Eloquents/Administrator
# php artisan make:model Models/Eloquents/Group

# php artisan make:command CreateAdminCommand
```