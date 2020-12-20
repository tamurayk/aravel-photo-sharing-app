<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Home;

use App\Http\Controllers\User\Post\PostStoreController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class PostStoreControllerTest extends AppTestCase
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
                'actionName' => PostStoreController::class,
                'routeName' => 'post.store',
            ],
            'POST',
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
            'POST',
            $baseUrl. '/create'
        );
    }

    public function testStore_未ログインの場合はログイン画面にリダイレクト()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        $response = $this->post('/create');

        // 未認証の場合は、login 画面にリダイレクトする事
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/login');
        $response->assertRedirect('http://localhost/login');
    }

    public function testStore()
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('original.jpeg');

        // 認証
        $user = factory(\App\Models\Eloquents\User::class)->create();
        $authUser = $this->actingAs($user, 'user');

        // 認証済みである事を確認
        $this->assertAuthenticated('user');

        // HTTP リクエスト
        $data = [
            'caption' => str_repeat('a', 255),
            'image' => $image,
        ];
        $response = $authUser->post('/create', $data);
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/create');
        $response->assertRedirect('http://localhost/create');

        $this->markTestIncomplete();
        //TODO: posts にレコードが追加されている事
        //TODO: app/public/posts/1 に画像が uuid.jpeg で保存されている事
    }
}
