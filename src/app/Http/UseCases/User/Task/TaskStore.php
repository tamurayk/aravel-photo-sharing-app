<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Task;

use App\Http\UseCases\User\Task\Interfaces\TaskStoreInterface;
use App\Models\Interfaces\TaskInterface;

class TaskStore implements TaskStoreInterface
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
     * @param int $useId
     * @param array $data
     * @return bool
     */
    public function __invoke(int $useId, array $data): bool
    {
        $fill = array_merge($data, [
            'user_id' => $useId,
        ]);

        $task = $this->taskEloquent->newInstance($fill);
        return $task->save();
    }
}
