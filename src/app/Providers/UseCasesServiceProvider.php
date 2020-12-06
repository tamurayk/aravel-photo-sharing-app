<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class UseCasesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Task
         */
        $this->app->bind(
            \App\Http\UseCases\User\Task\Interfaces\TaskIndexInterface::class,
            \App\Http\UseCases\User\Task\TaskIndex::class
        );
        $this->app->bind(
            \App\Http\UseCases\User\Task\Interfaces\TaskStoreInterface::class,
            \App\Http\UseCases\User\Task\TaskStore::class
        );
        $this->app->bind(
            \App\Http\UseCases\User\Task\Interfaces\TaskDestroyInterface::class,
            \App\Http\UseCases\User\Task\TaskDestroy::class
        );

        /**
         * Admin/User
         */
        $this->app->bind(
            \App\Http\UseCases\Admin\User\Interfaces\UserIndexInterface::class,
            \App\Http\UseCases\Admin\User\UserIndex::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
