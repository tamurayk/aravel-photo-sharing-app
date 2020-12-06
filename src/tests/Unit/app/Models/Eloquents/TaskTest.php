<?php
declare(strict_types=1);

namespace Test\Unit\app\Models\Eloquents;

use App\Models\Eloquents\Task;
use App\Models\Eloquents\User;
use App\Models\Interfaces\BaseInterface;
use App\Models\Interfaces\TaskInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class TaskTest extends AppTestCase
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
     * @test
     */
    public function testExtendAndImplements()
    {
        $taskEloquent = new Task();
        $this->assertTrue(is_subclass_of($taskEloquent, BaseInterface::class));
        $this->assertTrue(is_subclass_of($taskEloquent, TaskInterface::class));
    }

    /**
     * @test
     * @group save
     */
    public function user_id未指定でsaveした場合は例外がthrowされる事()
    {
        $fill = [
            'name' => 'test',
        ];

        $taskEloquent = new Task();

        // user_id 未指定の場合は、例外が throw される事
        $this->expectException(\PDOException::class);
        $this->expectExceptionCode('HY000');
        $this->expectExceptionMessage('\'user_id\' doesn\'t have a default value');

        $task = $taskEloquent->newInstance($fill);
        $result = $task->save();
    }

    /**
     * @test
     * @group save
     */
    public function 存在しないuser_idを指定してsaveした場合は例外がthrowされる事()
    {
        $fill = [
            'user_id' => 1,
            'name' => 'test',
        ];

        $taskEloquent = new Task();

        // user_id 未指定の場合は、例外が throw される事
        $this->expectException(\PDOException::class);
        $this->expectExceptionCode('23000');
        $this->expectExceptionMessage('a foreign key constraint fails');

        $task = $taskEloquent->newInstance($fill);
        $result = $task->save();
    }

    /**
     * @test
     * @group save
     */
    public function saveできる事()
    {
        Carbon::setTestNow(Carbon::parse('2020-01-01 00:00:00'));

        factory(User::class)->create([
            'id' => 1,
            'name' => 'aaa'
        ]);

        $this->assertEquals(0, DB::table('tasks')->count(), 'テスト開始時点で tasks テーブルが空である事を確認');

        $fill = [
            'user_id' => 1,
            'name' => 'test',
        ];

        $taskEloquent = new Task();
        $task = $taskEloquent->newInstance($fill);
        $result = $task->save();

        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('tasks')->count(), 'レコードが1件追加されている事');
        $this->assertDatabaseHas('tasks', [
            'user_id' => 1,
            'name' => 'test',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);
    }

    /**
     * @test
     */
    public function testFillable()
    {
        Carbon::setTestNow(Carbon::parse('2020-01-01 00:00:00'));

        factory(User::class)->create([
            'id' => 1,
            'name' => 'aaa'
        ]);

        $this->assertEquals(0, DB::table('tasks')->count(), 'テスト開始時点で tasks テーブルが空である事を確認');

        $fill = [
            'id' => 2,
            'user_id' => 1,
            'name' => 'test',
            'created_at' => '2020-12-31 00:00:00',
            'updated_at' => '2020-12-31 00:00:00',
        ];

        $taskEloquent = new Task();
        $task = $taskEloquent->newInstance($fill);
        $result = $task->save();

        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('tasks')->count(), 'レコードが1件追加されている事');
        $this->assertDatabaseHas('tasks', [
            'id' => 1, //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'user_id' => 1,
            'name' => 'test',
            'created_at' => '2020-01-01 00:00:00', //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'updated_at' => '2020-01-01 00:00:00', //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
        ]);
    }

    /**
     * @test
     * @group relation
     */
    public function testRelation()
    {
        factory(User::class)->create([
            'id' => 1,
            'name' => 'aaa'
        ]);
        factory(Task::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'bbb'
        ]);

        $taskEloquent = new Task();
        /** @var Task $task */
        $task = $taskEloquent->newQuery()->find(1);

        $this->assertEquals(1, $task->id);
        $this->assertEquals(1, $task->user_id);
        $this->assertEquals('bbb', $task->name);

        $taskUser = $task->user;
        $this->assertEquals(1, $taskUser->id, 'リレーションで user を取得できる事');
        $this->assertEquals('aaa', $taskUser->name, 'リレーションで user を取得できる事');
    }
}
