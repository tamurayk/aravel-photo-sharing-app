<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Home;

use App\Http\Controllers\User\Post\PostIndexController;
use App\Http\Controllers\User\Post\PostStoreController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class PostIndexControllerTest extends AppTestCase
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
                'actionName' => PostIndexController::class,
                'routeName' => 'post.index',
            ],
            'GET',
            $baseUrl. '/tamurayk'
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
                    //'auth:user', //認証なし
                ],
            ],
            'GET',
            $baseUrl. '/tamurayk'
        );
    }

    public function testIndex()
    {
        $this->markTestIncomplete();
    }
}
