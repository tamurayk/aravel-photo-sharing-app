<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PostCreateController extends Controller
{
    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('user.post.create');
    }
}
