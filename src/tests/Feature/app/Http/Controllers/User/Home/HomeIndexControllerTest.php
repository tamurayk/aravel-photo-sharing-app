<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Home;

use App\Http\Controllers\User\Home\HomeIndexController;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class HomeIndexControllerTest extends AppTestCase
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
                'actionName' => HomeIndexController::class,
                'routeName' => 'home.index',
            ],
            'GET',
            $baseUrl. '/home'
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
                    'auth:user',
                ],
            ],
            'GET',
            $baseUrl. '/home'
        );
    }
}
