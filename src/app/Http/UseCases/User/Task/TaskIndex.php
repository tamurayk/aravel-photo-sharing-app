<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Task;

use App\Http\UseCases\User\Task\Interfaces\TaskIndexInterface;
use App\Models\Constants\TaskConstants;
use App\Models\Interfaces\TaskInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class TaskIndex implements TaskIndexInterface
{
    /** @var TaskInterface  */
    private $taskEloquent;

    /**
     * TaskIndexController constructor.
     * @param TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->taskEloquent = $task;
    }

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
    ): LengthAwarePaginator {
        $perPage = Arr::get($paginatorParam, 'perPage') ?? TaskConstants::PER_PAGE;
        $orderColumn = Arr::get($paginatorParam, 'column') ?? 'created_at';
        $orderDirection = Arr::get($paginatorParam, 'direction') ?? 'desc';

        $query = $this->taskEloquent->newQuery()
            ->where('user_id', $userId)
            ->orderBy($orderColumn, $orderDirection);

        if (Arr::get($searchParam, 'name')) {
            $query->where('name', 'like','%' . $searchParam['name'] . '%');
        }

        // paginate() メソッド実行時に、HTTP Request の page クエリ文字列から対象ページを自動取得し、SQL に limit と offset が付与される
        $paginator = $query->paginate($perPage);

        return $paginator;
    }
}
