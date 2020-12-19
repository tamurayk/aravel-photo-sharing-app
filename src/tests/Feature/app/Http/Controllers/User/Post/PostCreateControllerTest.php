<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Home;

use App\Http\Controllers\User\Post\PostCreateController;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class PostCreateControllerTest extends AppTestCase
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
                'actionName' => PostCreateController::class,
                'routeName' => 'post.create',
            ],
            'GET',
            $baseUrl. '/create'
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
            $baseUrl. '/create'
        );
    }
}
