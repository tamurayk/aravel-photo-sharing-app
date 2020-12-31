<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

class PostIndexController extends Controller
{
    public function __invoke(string $userName, Guard $guard): View
    {
        \Log::debug($userName);
        return view('user.post.index');
    }
}
