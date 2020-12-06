<?php
declare(strict_types=1);

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

    public function testRouting()
    {
        $this->initAssertRouting();

        $baseUrl = config('app.url');
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@showLoginForm',
                'routeName' => 'login',

            ],
            'GET',
            $baseUrl . '/login'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@login',
                'routeName' => '',
            ],
            'POST',
            $baseUrl . '/login'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@logout',
                'routeName' => 'logout',
            ],
            'POST',
            $baseUrl . '/logout'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@redirectToProvider',
                'routeName' => 'oauth.login',
            ],
            'GET',
            $baseUrl . '/login/github'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@handleProviderCallback',
                'routeName' => 'oauth.callback',
            ],
            'GET',
            $baseUrl . '/login/github/callback'
        );
    }

    public function testMiddleware()
    {
        $this->initAssertRouting();

        $baseUrl = config('app.url');
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'user',
                ],
            ],
            'GET',
            $baseUrl . '/login'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'user',
                ],
            ],
            'POST',
            $baseUrl . '/login'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'user',
                ],
            ],
            'POST',
            $baseUrl . '/logout'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'user',
                    'guest:user',
                ],
            ],
            'GET',
            $baseUrl . '/login/github'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'user',
                    'guest:user',
                ],
            ],
            'GET',
            $baseUrl . '/login/github/callback'
        );
    }
}
