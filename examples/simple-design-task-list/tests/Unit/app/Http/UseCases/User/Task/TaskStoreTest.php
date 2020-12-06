<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Task;

use App\Http\UseCases\User\Task\TaskStore;
use App\Models\Eloquents\Task;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class TaskStoreTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUseCase()
    {
        $this->assertEquals(0, DB::table('tasks')->count(), 'テスト開始時点で tasks テーブルが空である事を確認');

        $useCase = new TaskStore(new Task());

        // run UseCase
        $result = $useCase(1, ['name' => 'task A']);

        // assert
        $this->assertTrue($result, 'UseCase から true が返される事');
        $this->assertEquals(1, DB::table('tasks')->count(), 'tasks テーブルにレコードが 1 件追加されている事');
        $task = DB::table('tasks')->where('user_id', 1)->first();
        $this->assertEquals('task A', $task->name, 'タスク名が正しく登録されている事');

        // run UseCase
        $result = $useCase(1, ['name' => 'task B']);

        // assert
        $this->assertTrue($result, 'UseCase から true が返される事');
        $this->assertEquals(2, DB::table('tasks')->count(), 'tasks テーブルにレコードが 1 件追加されている事');
        $task = DB::table('tasks')->where('user_id', 1)->orderByDesc('id')->first();
        $this->assertEquals('task B', $task->name, 'タスク名が正しく登録されている事');

        // run UseCase
        $result = $useCase(2, ['name' => 'hoge']);

        // assert
        $this->assertTrue($result, 'UseCase から true が返される事');
        $this->assertEquals(3, DB::table('tasks')->count(), 'tasks テーブルにレコードが 1 件追加されている事');
        $tasks = DB::table('tasks')->where('user_id', 2)->get();
        $this->assertCount(1, $tasks, 'user_id=2 のタスクが 1 件作成されている事');
        $this->assertEquals('hoge', $tasks[0]->name, 'タスク名が正しく登録されている事');
    }
}
