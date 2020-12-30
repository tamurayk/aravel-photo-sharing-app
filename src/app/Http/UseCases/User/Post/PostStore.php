<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post;

use App\Http\UseCases\User\Post\Exceptions\PostStoreException;
use App\Http\UseCases\User\Post\Interfaces\PostStoreInterface;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\User;
use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserInterface;

class PostStore implements PostStoreInterface
{
    /** @var Post  */
    private $postEloquent;

    /** @var User */
    private $userEloquent;

    public function __construct(PostInterface $post, UserInterface $user)
    {
        $this->postEloquent = $post;
        $this->userEloquent = $user;
    }

    /**
     * @param int $userId
     * @param array $data
     * @return bool
     * @throws PostStoreException
     */
    public function __invoke(
        int $userId,
        array $data
    ): bool {
        $fill = array_merge($data, [
            'user_id' => $userId,
        ]);

        try {
            $this->userEloquent->newQuery()->findOrFail($userId);
            $post = $this->postEloquent->newInstance($fill);
            return $post->save();
        } catch (\Exception $e) {
            throw new PostStoreException(
                'Failed to store post.',
                is_numeric($e->getCode()) ? $e->getCode() : 500,
                $e //Throwable $previous
            );
        }
    }
}
