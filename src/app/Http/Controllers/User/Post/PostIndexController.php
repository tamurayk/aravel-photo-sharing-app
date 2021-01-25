<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use App\Http\UseCases\User\Post\Exceptions\PostIndexException;
use App\Http\UseCases\User\Post\Interfaces\PostIndexInterface;
use App\Models\Eloquents\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class PostIndexController
 * @package App\Http\Controllers\User\Post
 */
class PostIndexController extends Controller
{
    public function __invoke(
        string $userName,
        Guard $guard,
        Request $request,
        PostIndexInterface $useCase
    ): View {
        /** @var User $user */
        $user = $guard->user();

        $paginatorParams = [
            'perPage' => $request->query('perPage'),
            'column' => $request->query('column'),
            'direction' => $request->query('direction'),
        ];

        try {
            $paginator = $useCase($userName, $paginatorParams);
        } catch (\Exception $e) {
            if ($e instanceof PostIndexException && $e->getCode() === 404) {
                \Log::debug(sprintf('%s %s', $e->getCode(), $e->getMessage()));
                abort(404);
            }
            throw $e;
        }

        // TODO: 自分の投稿一覧かどうかでbladeを分ける?
        return view('user.post.index', [
            'paginator' => $paginator->appends($request->query()),
            'isMine' => $user && $user->isMine($userName),
        ]);
    }
}
