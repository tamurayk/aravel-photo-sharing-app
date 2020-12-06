<?php

namespace Tests\Feature\app\Http\Controllers\User\Task;

use App\Http\Controllers\User\Task\TaskStoreController;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;
use Tests\Traits\RoutingTestTrait;

class TaskStoreControllerTest extends AppTestCase
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

    /**
     * Override to \Tests\Traits\RoutingTestTrait::RoutingTestDataProvider
     * @return array
     */
    public function RoutingTestDataProvider()
    {
        $this->createApplication();
        $baseUrl = config('app.url');

        return [
            [
                'POST',
                $baseUrl. '/task',
                TaskStoreController::class,
                'task.store',
            ],
        ];
    }

    /**
     * @test
     */
    public function index_未ログインの場合はログイン画面にリダイレクト()
    {
        // 認証されていない事を確認
        $this->assertGuest(null);

        $response = $this->post('/task');

        // 未認証の場合は、login 画面にリダイレクトする事
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/login');
        $response->assertRedirect('http://localhost/login');
    }

    /**
     * @test
     */
    public function index_ログイン中の場合はタスクを新規作成しタスク一覧にリダイレクト()
    {
        $this->assertEquals(0, DB::table('tasks')->count(), 'HTTPリクエスト前は tasks テーブルが空である事を確認');

        // 認証
        $user = factory(\App\Models\Eloquents\User::class)->create([
            'id' => 1,
        ]);
        $authUser = $this->actingAs($user, 'user');

        // HTTP リクエスト
        $data = [
            'name' => 'test'
        ];
        $response = $authUser->post('/task', $data);

        // タスク一覧にリダイレクトする事
        $response->assertStatus(302);
        $response->assertLocation('http://localhost/tasks');
        $response->assertRedirect('http://localhost/tasks');

        // tasks テーブルにレコードが追加される事
        $this->assertEquals(1, DB::table('tasks')->count(), 'HTTPリクエスト後に tasks テーブルに 1 レコード追加されている事');
        $task = DB::table('tasks')->find(1);
        $this->assertEquals(1, $task->user_id, '追加されたレコードの user_id が認証ユーザーの id になっている事');
        $this->assertEquals('test', $task->name, '追加されたレコードの user_id が認証ユーザーの id になっている事');
    }
}
