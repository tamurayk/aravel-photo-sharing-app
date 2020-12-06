<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Task;

use App\Http\UseCases\User\Task\TaskIndex;
use App\Models\Eloquents\Task;
use App\Models\Eloquents\User;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class TaskIndexTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testUseCase()
    {
        // Generate test data.
        factory(User::class)->create([
            'id' => 1,
        ]);
        factory(User::class)->create([
            'id' => 2,
        ]);
        factory(Task::class, 2)->create([
            'user_id' => 1,
        ]);
        factory(Task::class, 1)->create([
            'user_id' => 2,
        ]);
        $this->assertEquals(3, DB::table('tasks')->count(), 'tasks テーブルにテストデータが 3 件生成されている事');

        // generate UseCase
        $useCase = new TaskIndex(new Task());

        // run UseCase
        $useCaseOutput = $useCase(1);

        // assert
        $this->assertCount(2, $useCaseOutput, 'user_id=1 の task のみを取得している事');
        foreach ($useCaseOutput as $task) {
            $this->assertEquals(1, $task->user_id, 'user_id=1 の task のみを取得している事');
        }

        // run UseCase
        $useCaseOutput = $useCase(2);

        // assert
        $this->assertCount(1, $useCaseOutput, 'user_id=2 の task のみを取得している事');
        foreach ($useCaseOutput as $task) {
            $this->assertEquals(2, $task->user_id, 'user_id=2 の task のみを取得している事');
        }
    }

    /**
     * @test
     */
    public function testPaginator()
    {
        $this->markTestIncomplete('ページネーションのテストを書く');
    }

    /**
     * @test
     */
    public function search_検索結果が正しい事()
    {
        // Generate test data.
        factory(User::class)->create([
            'id' => 1,
        ]);
        factory(User::class)->create([
            'id' => 2,
        ]);
        factory(Task::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'keyword'
        ]);
        factory(Task::class)->create([
            'id' => 2,
            'user_id' => 1,
            'name' => 'xxxxkeywordxxxx'
        ]);
        factory(Task::class)->create([
            'id' => 3,
            'user_id' => 1,
            'name' => 'foobar'
        ]);
        factory(Task::class)->create([
            'id' => 4,
            'user_id' => 2,
            'name' => 'keyword'
        ]);
        $this->assertEquals(4, DB::table('tasks')->count(), 'tasks テーブルに意図した件数のテストデータが生成されている事');

        // generate UseCase
        $useCase = new TaskIndex(new Task());

        // run UseCase
        $searchParam = [
            'name' => 'keyword'
        ];
        $useCaseOutput = $useCase(1, $searchParam);

        // assert
        $this->assertCount(2, $useCaseOutput, '検索結果が正しい事(tasks.name を部分一致で検索した結果になっている事)');
        $this->assertEquals('keyword', $useCaseOutput->items()[0]->name, '検索結果が正しい事(tasks.name を部分一致で検索した結果になっている事)');
        $this->assertEquals('xxxxkeywordxxxx', $useCaseOutput->items()[1]->name, '検索結果が正しい事(tasks.name を部分一致で検索した結果になっている事)');
    }
}
