<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Post\Interfaces\PostStoreRequestInterface;
use App\Http\Requests\User\Post\PostStoreRequest;
use App\Http\UseCases\User\Post\Interfaces\PostStoreInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

class PostStoreController extends Controller
{
    /**
     * @param Guard $guard
     * @param PostStoreRequestInterface $request
     * @param PostStoreInterface $useCase
     * @return RedirectResponse
     */
    public function __invoke(
        Guard $guard,
        PostStoreRequestInterface $request,
        PostStoreInterface $useCase
    ): RedirectResponse {
        $userId = $guard->user()->id;

        /** @var PostStoreRequest $request */
        $validated = $request->validated();
        $uploadedImage = $request->file('image');

        // note:
        //  Controllerで複数のuseCaseの実行順序ハンドリングをするべきでないので、
        //  Controllerから呼び出すuseCaseを1つにまとめ、useCase内でサブuseCaseを呼ぶようにした
        $useCase($userId, $validated, $uploadedImage);

        $userName = Arr::get($guard->user()->user_profile, 'name');
        if ($userName) {
            //TODO: flash で success メッセージを出す
            return redirect()->route('post.index', ['userName' => $userName]);
        } else {
            //FIXME: ユーザー登録時にユーザー名を必須項目にするまでの暫定処理
            return redirect()->route('home.index');
        }
    }
}
