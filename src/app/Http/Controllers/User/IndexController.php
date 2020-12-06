<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

class IndexController extends AppController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('user.welcome');
    }
}
