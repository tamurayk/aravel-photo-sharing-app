<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** @var TaskRepository  */
    private $tasks;

    public function __construct(TaskRepository $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @param Request $request a
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|max:10',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('task.index');
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        // note: 1st arg of authorize() is Policy name that is related Task Model.
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }
}
