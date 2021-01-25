<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Home;

use App\Http\Controllers\User\Post\PostIndexController;
use App\Models\Eloquents\User;
use App\Models\Eloquents\UserProfile;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;
use App\Models\Eloquents\Post;
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
        $response = $this->get('/user1');
        $response->assertStatus(404, '存在しないユーザー名を指定した場合は404');

        // user1 と user1のpost
        factory(UserProfile::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'user1',
        ]);
        factory(Post::class, 21)->create([
            'user_id' => 1,
        ]);

        // user2 と user1のpost
        factory(UserProfile::class)->create([
            'id' => 2,
            'user_id' => 2,
            'name' => 'user2',
        ]);
        factory(Post::class, 3)->create([
            'user_id' => 2,
        ]);

        // 認証されていない事を確認
        $this->assertGuest(null);

        // user1 の投稿一覧画面
        $response = $this->get('/user1');
        $response->assertStatus(200);
        $response->assertViewIs('user.post.index');
        $response->assertViewHas('paginator');
        $response->assertViewHas('isMine');
        $paginator = $response->viewData('paginator');
        foreach ($paginator->items() as $post) {
            $this->assertEquals(1, $post->user_id, 'user1 の投稿のみを取得している事');
        }
        $this->assertFalse($response->viewData('isMine'));

        $response = $this->get('/user2');
        $response->assertStatus(200);
        $response->assertViewIs('user.post.index');
        $response->assertViewHas('paginator');
        $response->assertViewHas('isMine');
        $paginator = $response->viewData('paginator');
        $this->assertCount(3, $paginator->items());
        foreach ($paginator->items() as $post) {
            $this->assertEquals(2, $post->user_id, 'user2 の投稿のみを取得している事');
        }
        $this->assertFalse($response->viewData('isMine'));
    }

    public function testIndex_paginatorParam()
    {
        $this->markTestIncomplete('paginatorParamを渡すテストを書く');
    }

    public function testIndex_isMine()
    {
        $user_1 = factory(User::class)->create([
            'id' => 1,
        ]);
        factory(UserProfile::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'user1',
        ]);

        factory(UserProfile::class)->create([
            'id' => 2,
            'user_id' => 2,
            'name' => 'user2',
        ]);

        $response = $this->get('/user1');
        $response->assertViewHas('isMine');
        $this->assertFalse($response->viewData('isMine'), '未ログインの場合はfalse');

        // user1 でログイン
        $authUser = $this->actingAs($user_1, 'user');

        $response = $authUser->get('/user1');
        $response->assertViewHas('isMine');
        $this->assertTrue($response->viewData('isMine'), 'ログイン中ユーザーのユーザー名を指定した場合はtrue');

        $response = $authUser->get('/user2');
        $response->assertViewHas('isMine');
        $this->assertFalse($response->viewData('isMine'), '他人のユーザー名以外を指定した場合はfalse');
    }
}
