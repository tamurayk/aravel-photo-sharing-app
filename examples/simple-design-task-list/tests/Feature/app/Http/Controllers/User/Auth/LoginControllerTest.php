<?php

namespace Tests\Feature\app\Http\Controllers\User\Auth;

use App\Http\Controllers\User\Auth\LoginController;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class LoginControllerTest extends AppTestCase
{
    use RoutingTestTrait;

    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Override to \Tests\Traits\RoutingTestTrait::RoutingTestDataProvider
     * @return array
     */
    public function RoutingTestDataProvider()
    {
        $this->createApplication();
        $baseUrl = config('app.url');

        return [
            [
                'GET',
                $baseUrl. '/login/github',
                LoginController::class . '@redirectToProvider',
                'oauth.login',
            ],
            [
                'GET',
                $baseUrl. '/login/github/callback',
                LoginController::class . '@handleProviderCallback',
                'oauth.callback',
            ],
        ];
    }
}
