<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post\Interfaces;

use App\Http\UseCases\User\Post\Exceptions\PostIndexException;
use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserProfileInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostIndexInterface
{
    public function __construct(PostInterface $post, UserProfileInterface $userProfile);

    /**
     * @param string $userName
     * @param array $paginatorParam
     * @return LengthAwarePaginator
     * @throws PostIndexException
     */
    public function __invoke(string $userName, array $paginatorParam = []): LengthAwarePaginator;
}
