<?php

namespace App\Providers;

use App\SocialiteProviders\MyOAuthProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMyOAuthProviderSocialite();
    }

    private function bootMyOAuthProviderSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'myoauthprovider',
            function ($app) use ($socialite) {
                $config = $app['config']['services.myoauthprovider'];
                return $socialite->buildProvider(MyOAuthProvider::class, $config);
            }
        );
    }
}
