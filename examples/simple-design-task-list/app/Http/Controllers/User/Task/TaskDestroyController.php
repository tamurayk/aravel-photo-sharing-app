<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Task;

use App\Http\Controllers\User\AppController;
use App\Http\UseCases\User\Task\Interfaces\TaskDestroyInterface;
use App\Models\Eloquents\Task;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class TaskDestroyController extends AppController
{
    /**
     * @param Guard $guard
     * @param Task $task
     * @param TaskDestroyInterface $useCase
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(
        Guard $guard,
        Task $task, // TaskEloquentModel Passed by Implicit Binding. See: https://laravel.com/docs/6.x/routing#implicit-binding
        TaskDestroyInterface $useCase
    ): RedirectResponse {
        // note: 1st arg of authorize() is Policy name that is related Task Model.
        $this->authorize('destroy', $task);

        $useCase($guard->user()->id, $task->id);

        return redirect('/tasks');
    }
}
