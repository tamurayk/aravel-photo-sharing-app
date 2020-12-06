<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Task\Interfaces;

use App\Models\Interfaces\TaskInterface;

interface TaskStoreInterface
{
    public function __construct(TaskInterface $task);

    public function __invoke(int $userId, array $data): bool ;
}
