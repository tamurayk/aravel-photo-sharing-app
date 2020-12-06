<?php
declare(strict_types=1);

namespace App\Http\UseCases\Admin\User;

use App\Http\UseCases\Admin\User\Interfaces\UserIndexInterface;
use App\Models\Constants\UserConstants;
use App\Models\Interfaces\UserInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class UserIndex implements UserIndexInterface
{
    /** @var UserInterface  */
    private $userEloquent;

    public function __construct(UserInterface $user)
    {
        $this->userEloquent = $user;
    }

    /**
     * @param array $paginatorParam
     * @return LengthAwarePaginator
     */
    public function __invoke(
        array $paginatorParam = []
    ): LengthAwarePaginator {
        $perPage = Arr::get($paginatorParam, 'perPage') ?? UserConstants::PER_PAGE;
        $orderColumn = Arr::get($paginatorParam, 'column') ?? 'id';
        $orderDirection = Arr::get($paginatorParam, 'direction') ?? 'desc';

        $query = $this->userEloquent->newQuery()
            ->orderBy($orderColumn, $orderDirection);

        $paginator = $query->paginate($perPage);

        return $paginator;
    }
}
