<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class ModelsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Models\Interfaces\UserInterface::class,
            \App\Models\Eloquents\User::class
        );
        $this->app->bind(
            \App\Models\Interfaces\TaskInterface::class,
            \App\Models\Eloquents\Task::class
        );
        $this->app->bind(
            \App\Models\Interfaces\GroupInterface::class,
            \App\Models\Eloquents\Group::class
        );
        $this->app->bind(
            \App\Models\Interfaces\AdministratorInterface::class,
            \App\Models\Eloquents\Administrator::class
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