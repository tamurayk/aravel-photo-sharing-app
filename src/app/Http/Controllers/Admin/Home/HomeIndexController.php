<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin\Home;

use App\Http\Controllers\Admin\AdminController;

class HomeIndexController extends AdminController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke()
    {
        return view('admin.home');
    }
}
