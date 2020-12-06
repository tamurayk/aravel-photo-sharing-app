<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

class IndexController extends AdminController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('admin.welcome');
    }
}
