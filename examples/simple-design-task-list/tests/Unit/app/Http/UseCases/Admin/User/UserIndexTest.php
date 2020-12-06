<?php

namespace Test\Unit\app\Http\UseCases\Admin\User;

use App\Http\UseCases\Admin\User\UserIndex;
use App\Models\Eloquents\User;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class UserIndexTest extends AppTestCase
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
    public function testUseCase()
    {
        $this->assertEquals(0, DB::table('users')->count(), 'users テーブルが空である事を確認');

        $useCase = new UserIndex(new User());

        $useCaseOutput = $useCase();
        $this->assertEquals(0, $useCaseOutput->count(), 'users テーブルが空の場合は、空の Collection が返る事');

        factory(User::class, 2)->create();
        $this->assertEquals(2, DB::table('users')->count(), 'users テーブルにレコードが 2 件作成された事を確認');

        $useCaseOutput = $useCase();
        $this->assertEquals(2, $useCaseOutput->count(), 'users テーブルの全レコードを取得している事');
        foreach ($useCaseOutput as $user) {
            $this->assertInstanceOf(User::class, $user, 'User を取得している事');
        }
    }

    /**
     * @test
     */
    public function testPaginator()
    {
        $this->markTestIncomplete('ページネーションに対応したテストを書く');
    }
}

