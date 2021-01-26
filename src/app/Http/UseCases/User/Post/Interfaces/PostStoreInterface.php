<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post\Interfaces;

use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserInterface;
use Illuminate\Http\UploadedFile;

interface PostStoreInterface
{
    public function __construct(
        PostInterface $post,
        UserInterface $user,
        ImageStoreInterface $imageStoreUseCase
    );

    public function __invoke(
        int $userId,
        array $data,
        UploadedFile $uploadedFile
    ): bool;
}
