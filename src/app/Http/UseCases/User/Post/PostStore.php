<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post;

use App\Http\UseCases\User\Post\Exceptions\PostStoreException;
use App\Http\UseCases\User\Post\Interfaces\ImageStoreInterface;
use App\Http\UseCases\User\Post\Interfaces\PostStoreInterface;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\User;
use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserInterface;
use Illuminate\Http\UploadedFile;

class PostStore implements PostStoreInterface
{
    /** @var Post  */
    private $postEloquent;

    /** @var User */
    private $userEloquent;

    /** @var ImageStore */
    private $imageStoreUseCase;

    public function __construct(
        PostInterface $post,
        UserInterface $user,
        ImageStoreInterface $imageStoreUseCase
    ) {
        $this->postEloquent = $post;
        $this->userEloquent = $user;
        $this->imageStoreUseCase = $imageStoreUseCase;
    }

    /**
     * @param int $userId
     * @param array $data
     * @param UploadedFile $uploadedFile
     * @return bool
     * @throws PostStoreException
     */
    public function __invoke(
        int $userId,
        array $data,
        UploadedFile $uploadedFile
    ): bool {
        try {
            // 画像を保存
            $savedFileName = $this->imageStoreUseCase->__invoke($userId, $uploadedFile);

            $fill = array_merge($data, [
                'user_id' => $userId,
                'image' => $savedFileName,
            ]);

            $this->userEloquent->newQuery()->findOrFail($userId);
            $post = $this->postEloquent->newInstance($fill);
            if (!$post->save())
            {
                throw new \Exception();
            }
            return true;
        } catch (\Exception $e) {
            throw new PostStoreException(
                'Failed to store post.',
                is_numeric($e->getCode()) ? $e->getCode() : 500,
                $e //Throwable $previous
            );
        }
    }
}
