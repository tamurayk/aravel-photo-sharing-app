<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Post;

use App\Http\UseCases\User\Post\Exceptions\PostIndexException;
use App\Http\UseCases\User\Post\PostIndex;
use App\Models\Constants\PostConstants;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\UserProfile;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\AppTestCase;

class PostIndexTest extends AppTestCase
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
        factory(UserProfile::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'john',
        ]);

        factory(Post::class)->create([
            'id' => 1,
            'user_id' => 1,
        ]);
        factory(Post::class)->create([
            'id' => 2,
            'user_id' => 1,
        ]);
        factory(Post::class)->create([
            'id' => 3,
            'user_id' => 2,
        ]);

        $useCase = new PostIndex(new Post(), new UserProfile());
        $paginator = $useCase('john');
        $this->assertTrue($paginator instanceof LengthAwarePaginator, 'LengthAwarePaginator が return されている事');
        $this->assertEquals(2, count($paginator->items()), 'post を2件取得している事');
        foreach ($paginator->items() as $item) {
            $this->assertEquals(1, $item->user_id, 'user_id=1 の post のみを取得している事');
        }
    }

    public function testUseCase_存在しないuserNameを指定した場合は404()
    {
        $this->expectException(PostIndexException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('The specified user name does not exist.');

        $useCase = new PostIndex(new Post(), new UserProfile());
        $paginator = $useCase('john');
    }

    public function testUseCase_Paginator()
    {
        factory(UserProfile::class)->create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'john',
        ]);

        factory(Post::class)->create([
            'id' => 1,
            'user_id' => 1,
            'image' => 'c',
            'created_at' => '2021-01-10 00:00:00',
        ]);
        factory(Post::class)->create([
            'id' => 2,
            'user_id' => 1,
            'image' => 'b',
            'created_at' => '2021-01-12 00:00:00',
        ]);
        factory(Post::class)->create([
            'id' => 3,
            'user_id' => 1,
            'image' => 'a',
            'created_at' => '2021-01-11 00:00:00',
        ]);

        $useCase = new PostIndex(new Post(), new UserProfile());

        $paginator = $useCase('john');
        $this->assertEquals(3, count($paginator->items()));
        $this->assertEquals(3, $paginator->total());
        $this->assertEquals(1, $paginator->lastPage());
        $this->assertEquals(PostConstants::PER_PAGE, $paginator->perPage());
        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(2, $paginator->items()[0]->id, 'paginatorParam を渡さなかった場合は、created_at の降順にソートされている事');
        $this->assertEquals(3, $paginator->items()[1]->id, 'paginatorParam を渡さなかった場合は、created_at の降順にソートされている事');
        $this->assertEquals(1, $paginator->items()[2]->id, 'paginatorParam を渡さなかった場合は、created_at の降順にソートされている事');

        $paginatorParam = [
            'perPage' => 2,
            'column' => 'image',
            'direction' => 'asc'
        ];
        $paginator = $useCase('john', $paginatorParam);
        $this->assertEquals(2, count($paginator->items()));
        $this->assertEquals(3, $paginator->total());
        $this->assertEquals(3, $paginator->total());
        $this->assertEquals(2, $paginator->lastPage());
        $this->assertEquals(2, $paginator->perPage());
        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(3, $paginator->items()[0]->id, 'image 昇順にソートされている事');
        $this->assertEquals(2, $paginator->items()[1]->id, 'image 昇順にソートされている事');
    }
}
