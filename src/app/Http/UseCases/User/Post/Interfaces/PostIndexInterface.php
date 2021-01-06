<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post\Interfaces;

use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserProfileInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostIndexInterface
{
    public function __construct(PostInterface $post, UserProfileInterface $userProfile);

    public function __invoke(string $userName, array $paginatorParam = []): LengthAwarePaginator;
}
