<?php
declare(strict_types=1);

namespace Test\Unit\app\Models\Eloquents;

use App\Models\Eloquents\Eloquent;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\User;
use App\Models\Interfaces\BaseInterface;
use App\Models\Interfaces\PostInterface;
use Tests\AppTestCase;

class PostTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testExtendAndImplements()
    {
        $eloquent = new Post();
        $this->assertTrue(is_subclass_of($eloquent, Eloquent::class));
        $this->assertTrue(is_subclass_of($eloquent, BaseInterface::class));
        $this->assertTrue(is_subclass_of($eloquent, PostInterface::class));
    }

    public function testRelation()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);

        factory(Post::class)->create([
            'id' => 1,
            'user_id' => 1,
        ]);

        $postEloquent = new Post();
        /** @var Post $post */
        $post = $postEloquent->newQuery()->find(1);

        $this->assertEquals(1, $post->user()->id);
    }
}
