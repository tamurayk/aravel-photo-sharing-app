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
        $this->app->bind(
            \App\Http\UseCases\User\Auth\Interfaces\RegisterInterface::class,
            \App\Http\UseCases\User\Auth\Register::class
        );

        /**
         * Post
         */
        $this->app->bind(
            \App\Http\UseCases\User\Post\Interfaces\ImageStoreInterface::class,
            \App\Http\UseCases\User\Post\ImageStore::class
        );
        $this->app->bind(
            \App\Http\UseCases\User\Post\Interfaces\PostStoreInterface::class,
            \App\Http\UseCases\User\Post\PostStore::class
        );
        $this->app->bind(
            \App\Http\UseCases\User\Post\Interfaces\PostIndexInterface::class,
            \App\Http\UseCases\User\Post\PostIndex::class
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
