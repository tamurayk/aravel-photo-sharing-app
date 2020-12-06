<?php
declare(strict_types=1);

namespace App\Http\UseCases\Admin\User;

use App\Http\UseCases\Admin\User\Interfaces\UserIndexInterface;
use App\Models\Constants\UserConstants;
use App\Models\Eloquents\User;
use App\Models\Interfaces\UserInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class UserIndex implements UserIndexInterface
{
    /** @var User */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
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

        $query = $this->user->newQuery()
            ->orderBy($orderColumn, $orderDirection);

        $paginator = $query->paginate($perPage);

        return $paginator;
    }
}
