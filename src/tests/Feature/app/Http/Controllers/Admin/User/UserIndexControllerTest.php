<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\User\UserIndexController;
use App\Models\Constants\UserConstants;
use App\Models\Eloquents\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class UserIndexControllerTest extends AppTestCase
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
                'actionName' => UserIndexController::class,
                'routeName' => 'admin.user.index',
            ],
            'GET',
            $baseUrl. '/admin/users'
        );
    }

    /**
     * Override to \Tests\Traits\RoutingTestTrait::AppliedMiddlewareTestDataProvider
     * @return array
     */
    public function AppliedMiddlewareTestDataProvider()
    {
        return [
            [
            ],
        ];
    }

    public function testMiddleware()
    {
        $this->initAssertRouting();

        $baseUrl = config('app.url');
        $this->assertAppliedMiddleware(
            [
                'middleware' => [
                    'admin',
                    'auth:admin',
                ],
            ],
            'GET',
            $baseUrl . '/admin/users'
        );
    }

    /**
     * @test
     */
    public function index_未ログインの場合はログイン画面にリダイレクト()
    {
        // 未ログインの場合

        // ユーザーとしてログインしている場合

        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function index_管理者でログインしている場合は200が返る()
    {
        // 管理者ログイン

        // HTTPリクエスト

        // 200が返る事を確認
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function index_管理者でログインしている場合はユーザー一覧画面を表示()
    {
        // Generate test data.
        for ($i = 1; $i <= 3; $i++) {
            factory(User::class, 1)->create([
                'id' => $i,
                'name' => sprintf('user-%s', $i),
            ]);
        }
        $this->assertEquals(3, DB::table('users')->count());

        factory(\App\Models\Eloquents\Group::class)->create();
        $user = factory(\App\Models\Eloquents\Administrator::class)->create();
        $authUser = $this->actingAs($user, 'admin');

        $this->assertAuthenticated('admin');

        // HTTP request
        $response = $authUser->get('/admin/users');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.user.index');

        // Assert view vars
        $collection = new Collection([
            (new User())->newQuery()->find(3),
            (new User())->newQuery()->find(2),
            (new User())->newQuery()->find(1),
        ]);
        $expected = new LengthAwarePaginator(
            $collection,
            3,
            UserConstants::PER_PAGE,
            1,
            [
                'path' => sprintf('%s/admin/users', config('app.url')),
                'pageName' => 'page',
            ]
        );
        $response->assertViewHas('paginator', $expected);

        // Assert HTML
        $this->assertNotFalse(strpos($response->content(), '<title>Admin</title>'));
        $this->assertNotFalse(strpos($response->content(), 'user-3'));
        $this->assertNotFalse(strpos($response->content(), 'user-2'));
        $this->assertNotFalse(strpos($response->content(), 'user-1'));
        $this->assertFalse(strpos($response->content(), 'user-4'));
    }
}
