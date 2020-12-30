<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post\Interfaces;

use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserInterface;

interface PostStoreInterface
{
    public function __construct(PostInterface $post, UserInterface $user);

    public function __invoke(int $userId, array $data): bool;
}
