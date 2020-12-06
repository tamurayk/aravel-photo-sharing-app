<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Task;

use App\Http\UseCases\User\Task\Exceptions\TaskDestroyException;
use App\Http\UseCases\User\Task\TaskDestroy;
use App\Models\Eloquents\Task;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class TaskDestroyTest extends AppTestCase
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
        // Generate test data.
        factory(Task::class, 1)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'a',
        ]);
        factory(Task::class, 1)->create([
            'id' => 2,
            'user_id' => 1,
            'name' => 'b',
        ]);
        factory(Task::class, 1)->create([
            'id' => 3,
            'user_id' => 2,
            'name' => 'c',
        ]);
        $this->assertEquals(3, DB::table('tasks')->count(), 'tasks テーブルにテストデータが 3 件生成されている事');

        $useCase = new TaskDestroy(new Task());

        // run UseCase
        $result = $useCase(1, 2);
        // assert
        $this->assertTrue($result, '自分のタスクを削除できる事');
        $this->assertEquals(2, DB::table('tasks')->count(), '1 件削除されている事');
        $this->assertEquals(0, DB::table('tasks')->where('id', 2)->count(), 'id=2 の task が削除されている事');

        // 他人のタスクを削除しようとした場合は、例外が throw される事
        $this->expectException(TaskDestroyException::class);
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('Failed to delete task.');
        $result = $useCase(1, 3);
    }
}
