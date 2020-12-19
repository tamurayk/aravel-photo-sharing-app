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

    public function testCreate_未ログインの場合はログイン画面にリダイレクト()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        $response = $this->get('/create');

        // 未認証の場合は、login 画面にリダイレクトする事
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/login');
        $response->assertRedirect('http://localhost/login');
    }

    public function testCreate_ログイン中の場合は200が返る()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        // 認証
        $user = factory(\App\Models\Eloquents\User::class)->create();
        $authUser = $this->actingAs($user, 'user');

        // 認証済みである事を確認
        $this->assertAuthenticated('user');

        // HTTP リクエスト
        $response = $authUser->get('/create');

        // 認証済みユーザーからのリクエストの場合は 200 が返る事
        $response->assertStatus(200);
        // 正しい Blade ファイルを表示している事
        $response->assertViewIs('user.post.create');
    }
}
