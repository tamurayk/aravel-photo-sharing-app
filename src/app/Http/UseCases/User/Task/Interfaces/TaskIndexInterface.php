<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Task\Interfaces;

use App\Models\Interfaces\TaskInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskIndexInterface
{
    public function __construct(TaskInterface $task);

    /**
     * @param int $userId
     * @param array $searchParam
     * @param array $paginatorParam
     * @return LengthAwarePaginator
     */
    public function __invoke(
        int $userId,
        array $searchParam = [],
        array $paginatorParam = []
    ): LengthAwarePaginator;
}
