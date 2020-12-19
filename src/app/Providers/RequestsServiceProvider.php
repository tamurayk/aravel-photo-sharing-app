<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RequestsServiceProvider
 * @package App\Providers
 */
final class RequestsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Http\Requests\User\Post\Interfaces\PostStoreRequestInterface::class,
            \App\Http\Requests\User\Post\PostStoreRequest::class
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