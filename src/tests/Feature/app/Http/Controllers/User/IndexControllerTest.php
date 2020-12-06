<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User;

use App\Http\Controllers\User\IndexController;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class IndexControllerTest extends AppTestCase
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
                'actionName' => IndexController::class,
                'routeName' => 'index',
            ],
            'GET',
            $baseUrl. '/'
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
            $baseUrl. '/'
        );
    }
}
