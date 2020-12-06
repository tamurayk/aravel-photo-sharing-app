<?php
declare(strict_types=1);

namespace App\Http\UseCases\Admin\User\Interfaces;

use App\Models\Interfaces\UserInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserIndexInterface
{
    public function __construct(UserInterface $user);

    /**
     * @param array $paginatorParam
     * @return LengthAwarePaginator
     */
    public function __invoke(
        array $paginatorParam = []
    ): LengthAwarePaginator;
}
