<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Home;

use App\Http\Controllers\Controller;

class HomeIndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('user.home');
    }
}
