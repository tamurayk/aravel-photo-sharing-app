<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Task;

use App\Http\Controllers\User\AppController;
use App\Http\UseCases\User\Task\Interfaces\TaskIndexInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TaskIndexController extends AppController
{
    /**
     * @param Guard $guard
     * @param TaskIndexInterface $useCase
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Guard $guard, TaskIndexInterface $useCase, Request $request)
    {
        $userId = $guard->user()->id;
        $searchParams = [
            'name' => $request['name'],
        ];
        $paginatorParams = [
            'perPage' => $request->query('perPage'),
            'column' => $request->query('column'),
            'direction' => $request->query('direction'),
        ];

        $paginator = $useCase($userId, $searchParams, $paginatorParams);

        return view('user.task.index', [
            'paginator' => $paginator->appends($request->query()),
        ]);
    }
}
