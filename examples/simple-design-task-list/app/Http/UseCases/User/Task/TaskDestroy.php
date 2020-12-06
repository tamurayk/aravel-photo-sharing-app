<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Task;

use App\Models\Eloquents\Task;
use App\Models\Interfaces\TaskInterface;
use App\Http\UseCases\User\Task\Interfaces\TaskDestroyInterface;
use App\Http\UseCases\User\Task\Exceptions\TaskDestroyException;

class TaskDestroy implements TaskDestroyInterface
{
    /** @var Task */
    private $taskEloquent;

    public function __construct(TaskInterface $task)
    {
        $this->taskEloquent = $task;
    }

    /**
     * @param int $userId
     * @param int $taskId
     * @return bool
     * @throws TaskDestroyException
     */
    public function __invoke(int $userId, int $taskId): bool
    {
        $result = $this->taskEloquent->newQuery()
            ->where('id', $taskId)
            ->where('user_id', $userId)
            ->delete();

        if (!$result) {
            throw new TaskDestroyException('Failed to delete task.', 403);
        }

        return true;
    }
}
