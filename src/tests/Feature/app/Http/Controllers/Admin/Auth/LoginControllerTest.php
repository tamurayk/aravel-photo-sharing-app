<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Auth\LoginController;
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
                'routeName' => 'admin.login',
            ],
            'GET',
            $baseUrl . '/admin/login'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@login',
                'routeName' => '',
            ],
            'POST',
            $baseUrl. '/admin/login'
        );
        $this->assertDispatchedRoute(
            [
                'actionName' => LoginController::class . '@logout',
                'routeName' => 'admin.logout',
            ],
            'POST',
            $baseUrl . '/admin/logout'
        );
    }

    public function testMiddleware()
    {
        $this->initAssertRouting();

        $baseUrl = config('app.url');
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'admin',
                    'guest:admin',
                ],
            ],
            'GET',
            $baseUrl . '/admin'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'admin',
                    'guest:admin',
                ],
            ],
            'POST',
            $baseUrl . '/admin/login'
        );
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'admin',
                    'auth:admin',
                ],
            ],
            'POST',
            $baseUrl . '/admin/logout'
        );
    }
}
