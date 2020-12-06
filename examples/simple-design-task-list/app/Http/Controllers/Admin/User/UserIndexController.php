<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\AdminController;
use App\Http\UseCases\Admin\User\Interfaces\UserIndexInterface;
use Illuminate\Http\Request;

class UserIndexController extends AdminController
{
    public function __invoke(UserIndexInterface $useCase, Request $request)
    {
        $paginatorParams = [
            'perPage' => $request->query('perPage'),
            'column' => $request->query('column'),
            'direction' => $request->query('direction'),
        ];

        $paginator = $useCase($paginatorParams);

        return view('admin.user.index', [
            'paginator' => $paginator->appends($request->query()),
        ]);
    }
}
