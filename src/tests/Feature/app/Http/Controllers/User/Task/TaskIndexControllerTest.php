<?php
declare(strict_types=1);

namespace Tests\Feature\app\Http\Controllers\User\Task;

use App\Http\Controllers\User\Task\TaskIndexController;
use App\Models\Constants\TaskConstants;
use App\Models\Eloquents\Task;
use App\Models\Eloquents\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class TaskIndexControllerTest extends AppTestCase
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
                'actionName' => TaskIndexController::class,
                'routeName' => 'task.index',
            ],
            'GET',
            $baseUrl. '/tasks'
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
            $baseUrl. '/tasks'
        );
    }

    /**
     * @test
     */
    public function index_未ログインの場合はログイン画面にリダイレクト()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        $response = $this->get('/tasks');

        // 未認証の場合は、login 画面にリダイレクトする事
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/login');
        $response->assertRedirect('http://localhost/login');
    }

    /**
     * @test
     */
    public function index_ログイン中の場合は200が返る()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        // 認証
        $user = factory(\App\Models\Eloquents\User::class)->create();
        $authUser = $this->actingAs($user, 'user');

        // 認証済みである事を確認
        $this->assertAuthenticated('user');

        // HTTP リクエスト
        $response = $authUser->get('/tasks');

        // 認証済みユーザーからのリクエストの場合は 200 が返る事
        $response->assertStatus(200);
        // 正しい Blade ファイルを表示している事
        $response->assertViewIs('user.task.index');
    }

    /**
     * @test
     */
    public function index_ログイン中ユーザーのタスク一覧を表示()
    {
        // Generate test data.
        $user1 = factory(User::class)->create([
            'id' => 1,
        ]);
        $user2 = factory(User::class)->create([
            'id' => 2,
        ]);
        factory(Task::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'user 1 task 1'
        ]);
        factory(Task::class)->create([
            'id' => 2,
            'user_id' => 2,
            'name' => 'user 2 task 1'
        ]);
        factory(Task::class)->create([
            'id' => 3,
            'user_id' => 1,
            'name' => 'user 1 task 2'
        ]);

        // Check not authenticate.
        $this->assertGuest(null);

        /**
         * user_id = 1
         */
        // Add authentication.
        $authUser = $this->actingAs($user1, 'user');

        // Check authenticated.
        $this->assertAuthenticated('user');

        // Http request
        $response = $authUser->get('/tasks');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.task.index');

        // Assert view vars.
        $collection = new Collection([
            (new Task())->newQuery()->find(1),
            (new Task())->newQuery()->find(3),
        ]);
        $expected = new LengthAwarePaginator(
            $collection,
            2,
            TaskConstants::PER_PAGE,
            1,
            [
                'path' => sprintf('%s/tasks', config('app.url')),
                'pageName' => 'page',
            ]
        );
        $response->assertViewHas('paginator', $expected);

        // Assert HTML
        $this->assertNotFalse(strpos($response->content(), '<td class="table-text"><div>user 1 task 1</div></td>'));
        $this->assertNotFalse(strpos($response->content(), '<td class="table-text"><div>user 1 task 2</div></td>'));

        /**
         * user_id = 2
         */
        // Add authentication.
        $authUser = $this->actingAs($user2, 'user');

        // Http request
        $response = $authUser->get('/tasks');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.task.index');

        // Assert view vars.
        $collection = new Collection([
            (new Task())->newQuery()->find(2),
        ]);
        $expected = new LengthAwarePaginator(
            $collection,
            1,
            TaskConstants::PER_PAGE,
            1,
            [
                'path' => sprintf('%s/tasks', config('app.url')),
                'pageName' => 'page',
            ]
        );
        $response->assertViewHas('paginator', $expected);

        // Assert HTML
        $this->assertNotFalse(strpos($response->content(), '<td class="table-text"><div>user 2 task 1</div></td>'));
    }

    /**
     * @test
     */
    public function index_paginator()
    {
        // Generate test data.
        $user1 = factory(User::class)->create([
            'id' => 1,
        ]);
        $user2 = factory(User::class)->create([
            'id' => 2,
        ]);
        factory(Task::class, 21)->create([
            'user_id' => $user1->id,
        ]);
        factory(Task::class, 1)->create([
            'user_id' => $user2->id,
        ]);
        $this->assertEquals(21, DB::table('tasks')->where('user_id', $user1->id)->count());
        $this->assertEquals(1, DB::table('tasks')->where('user_id', $user2->id)->count());

        $authUser = $this->actingAs($user1, 'user');

        /**
         * 1ページ目
         */
        // Http request
        $response = $authUser->get('/tasks?perPage=10');
        $response->assertStatus(200);

        // view変数 $paginator を取得
        /** @var LengthAwarePaginator $paginator */
        $paginator = $response->viewData('paginator');

        // Assert
        $this->assertEquals(10, $paginator->perPage());
        $this->assertEquals(get_class($paginator),
            LengthAwarePaginator::class,
            'paginator が LengthAwarePaginator のインスタンスである事'
        );
        $this->assertEquals(21, $paginator->total());
        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(10, count($paginator->items()));
        $this->assertEquals(true, $paginator->hasMorePages());
        foreach ($paginator->items() as $task) {
            $this->assertEquals($user1->id, $task->user_id);
        }

        /**
         * 2ページ目
         */
        // Http request
        $response = $authUser->get('/tasks?perPage=10&page=2');
        $response->assertStatus(200);

        // view変数 $paginator を取得
        /** @var LengthAwarePaginator $paginator */
        $paginator = $response->viewData('paginator');

        // Assert
        $this->assertEquals(21, $paginator->total());
        $this->assertEquals(2, $paginator->currentPage());
        $this->assertEquals(10, count($paginator->items()));
        $this->assertEquals(true, $paginator->hasMorePages());
        foreach ($paginator->items() as $task) {
            $this->assertEquals($user1->id, $task->user_id);
        }

        /**
         * 3ページ目
         */
        // Http request
        $response = $authUser->get('/tasks?perPage=10&page=3');
        $response->assertStatus(200);

        // view変数 $paginator を取得
        /** @var LengthAwarePaginator $paginator */
        $paginator = $response->viewData('paginator');

        // Assert
        $this->assertEquals(21, $paginator->total());
        $this->assertEquals(3, $paginator->currentPage());
        $this->assertEquals(1, count($paginator->items()));
        $this->assertEquals(false, $paginator->hasMorePages());
        foreach ($paginator->items() as $task) {
            $this->assertEquals($user1->id, $task->user_id);
        }
    }

    /**
     * @test
     */
    public function index_検索()
    {
        $this->markTestIncomplete('検索機能のテストを書く');
    }
}
