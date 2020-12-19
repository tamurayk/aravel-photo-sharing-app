<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class PostStoreController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('post.create');
    }
}
