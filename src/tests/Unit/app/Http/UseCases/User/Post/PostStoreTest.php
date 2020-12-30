<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Task;

use App\Http\UseCases\User\Post\Exceptions\PostStoreException;
use App\Http\UseCases\User\Post\PostStore;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\User;
use Illuminate\Support\Facades\DB;
use Tests\AppTestCase;

class PostStoreTest extends AppTestCase
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
     * @throws PostStoreException
     */
    public function testUseCase()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);

        $this->assertEquals(0, DB::table('posts')->count(), '事前確認');

        $useCase = new PostStore(new Post(), new User());
        $data = [
            'caption' => 'a',
            'image' => 'b',
        ];
        $result = $useCase(1, $data);
        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('posts')->count());
        $post = DB::table('posts')->where('user_id', 1)->first();
        $this->assertEquals('a', $post->caption);
        $this->assertEquals('b', $post->image);
    }

    /**
     * @throws PostStoreException
     */
    public function testUseCase_dataが不正な場合は例外がthrowされる事()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);

        $this->expectException(PostStoreException::class);

        $data = [];
        $useCase = new PostStore(new Post(), new User());
        $result = $useCase(1, $data);
    }

    /**
     * @throws PostStoreException
     */
    public function testUseCase_存在しないuser_idを指定した場合は例外がthrowされる事()
    {
        $this->expectException(PostStoreException::class);

        $data = [
            'caption' => 'a',
            'image' => 'b',
        ];
        $useCase = new PostStore(new Post());
        $result = $useCase(1, $data);
    }
}
