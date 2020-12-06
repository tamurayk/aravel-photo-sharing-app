<?php
declare(strict_types=1);

namespace Test\Unit\app\Models\Eloquents;

use App\Models\Eloquents\Authenticatable;
use App\Models\Eloquents\Task;
use App\Models\Eloquents\User;
use App\Models\Interfaces\BaseInterface;
use App\Models\Interfaces\UserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\AppTestCase;

class UserTest extends AppTestCase
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
        $userEloquent = new User();
        $this->assertTrue(is_subclass_of($userEloquent, Authenticatable::class));
        $this->assertTrue(is_subclass_of($userEloquent, BaseInterface::class));
        $this->assertTrue(is_subclass_of($userEloquent, UserInterface::class));
    }

    /**
     * @test
     * @group relation
     */
    public function testRelation()
    {
        factory(User::class)->create([
            'id' => 1,
            'name' => 'user-1'
        ]);
        factory(Task::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'task-1'
        ]);
        factory(Task::class)->create([
            'id' => 2,
            'user_id' => 1,
            'name' => 'task-2'
        ]);

        $userEloquent = new User();
        /** @var User $user */
        $user = $userEloquent->newQuery()->find(1);

        $this->assertEquals(1, $user->id);

        $userTasks = $user->tasks->toArray();
        $this->assertCount(2, $userTasks, 'リレーションで tasks を取得できる事');
        $this->assertEquals(1, $userTasks[0]['id'], 'リレーションで tasks を取得できる事');
        $this->assertEquals(2, $userTasks[1]['id'], 'リレーションで tasks を取得できる事');
    }

    /**
     * @test
     * @group hidden
     */
    public function testHidden()
    {
        factory(User::class)->create([
            'id' => 1,
            'name' => 'user-1'
        ]);

        $userEloquent = new User();
        /** @var User $user */
        $userArray = $userEloquent->newQuery()->find(1)->toArray();

        $this->assertArrayHasKey('id', $userArray);
        $this->assertArrayHasKey('name', $userArray);
        $this->assertArrayHasKey('email', $userArray);
        $this->assertArrayHasKey('email_verified_at', $userArray, '配列に変換した際に含まれない事');
        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray, '配列に変換した際に含まれない事');
        $this->assertArrayHasKey('created_at', $userArray);
        $this->assertArrayHasKey('updated_at', $userArray);
    }

    /**
     * @test
     * @group casts
     */
    public function testCasts()
    {
        $this->markTestIncomplete('add test of $casts');
    }

    /**
     * @test
     * @group save
     */
    public function nameを指定せずにsaveした場合は例外がthrowされる事()
    {
        $fill = [
            'email' => 'b',
            'password' => 'c'
        ];

        $this->expectException(\PDOException::class);
        $this->expectExceptionCode('HY000');
        $this->expectExceptionMessage('\'name\' doesn\'t have a default value');

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();
    }

    /**
     * @test
     * @group save
     */
    public function emailを指定せずにsaveした場合は例外がthrowされる事()
    {
        $fill = [
            'name' => 'a',
            'password' => 'c'
        ];

        $this->expectException(\PDOException::class);
        $this->expectExceptionCode('HY000');
        $this->expectExceptionMessage('\'email\' doesn\'t have a default value');

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();
    }

    /**
     * @test
     * @group save
     */
    public function 登録済みのemailを指定してsaveした場合は例外がthrowされる事()
    {
        factory(User::class)->create([
            'email' => 'test',
        ]);

        $fill = [
            'name' => 'a',
            'email' => 'test',
            'password' => 'c'
        ];

        $this->expectException(\PDOException::class);
        $this->expectExceptionCode('23000');
        $this->expectExceptionMessage('Integrity constraint violation: 1062 Duplicate entry \'test\' for key \'users_email_unique\'');

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();
    }

    /**
     * @test
     * @group save
     */
    public function saveできる事()
    {
        Carbon::setTestNow(Carbon::parse('2020-01-01 00:00:00'));

        $this->assertEquals(0, DB::table('users')->count(), 'テスト開始時点でテーブルが空である事を確認');

        $hash = Hash::make('c');
        $fill = [
            'name' => 'a',
            'email' => 'b',
            'password' => $hash,
        ];

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();

        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('users')->count(), 'レコードが1件追加されている事');
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'a',
            'email' => 'b',
            'email_verified_at' => null,
            'password' => $hash,
            'remember_token' => null,
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);
    }

    /**
     * @test
     * @group save
     */
    public function passwordをnullでsaveできる事()
    {
        Carbon::setTestNow(Carbon::parse('2020-01-01 00:00:00'));

        $this->assertEquals(0, DB::table('users')->count(), 'テスト開始時点でテーブルが空である事を確認');

        $fill = [
            'name' => 'a',
            'email' => 'b',
            'password' => null,
        ];

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();

        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('users')->count(), 'レコードが1件追加されている事');
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => 'a',
            'email' => 'b',
            'email_verified_at' => null,
            'password' => null,
            'remember_token' => null,
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

        $this->assertEquals(0, DB::table('users')->count(), 'テスト開始時点でテーブルが空である事を確認');

        $hash = Hash::make('c');
        $fill = [
            'id' => 2,
            'name' => 'a',
            'email' => 'b',
            'email_verified_at' => 'foo',
            'password' => $hash,
            'remember_token' => 'bar',
            'created_at' => '2020-12-31 00:00:00',
            'updated_at' => '2020-12-31 00:00:00',
        ];

        $userEloquent = new User();
        $user = $userEloquent->newInstance($fill);
        $result = $user->save();

        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('users')->count(), 'レコードが1件追加されている事');
        $this->assertDatabaseHas('users', [
            'id' => 1, //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'name' => 'a',
            'email' => 'b',
            'email_verified_at' => null, //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'password' => $hash,
            'remember_token' => null, //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'created_at' => '2020-01-01 00:00:00', //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
            'updated_at' => '2020-01-01 00:00:00', //$fillable に定義されていないカラムの為、$fill で指定した値が反映されない事
        ]);
    }
}
