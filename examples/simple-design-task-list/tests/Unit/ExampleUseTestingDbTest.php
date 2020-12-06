<?php

namespace Tests\Unit;

use App\Models\Eloquents\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleUseTesting extends TestCase
{
    // テスト実行時に自動的にマイグレーションを実行 + テスト中に変更したレコードを元に戻す
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テスト実行前に config:clear Artisan コマンドで設定キャッシュをクリア
        Artisan::call('config:clear');
    }

    public function testExample()
    {
        $this->assertEquals(0, DB::table('users')->count(), 'factory() 実行前の user テーブルのレコード数が 0 件である事を確認');

        // テスト用のデータを 1 件生成
        //  database/factories/UserFactory.php の Faker でランダムなダミー値のデータが生成される
        factory(User::class)->create();

        $this->assertEquals(1, DB::table('users')->count(), 'factory() 実行後に user テーブルにレコードが 1 件追加された事を確認');
    }
}
