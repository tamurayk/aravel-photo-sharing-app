<?php
declare(strict_types=1);

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('welcome');
    }
}
