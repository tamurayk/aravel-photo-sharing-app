<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Post\Interfaces\PostStoreRequestInterface;
use App\Http\Requests\User\Post\PostStoreRequest;
use App\Http\UseCases\User\Post\Interfaces\ImageStoreInterface;
use App\Http\UseCases\User\Post\Interfaces\PostStoreInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

class PostStoreController extends Controller
{
    /**
     * @param Guard $guard
     * @param PostStoreRequestInterface $request
     * @param ImageStoreInterface $imageStoreUseCase
     * @param PostStoreInterface $postStoreUseCase
     * @return RedirectResponse
     */
    public function __invoke(
        Guard $guard,
        PostStoreRequestInterface $request,
        ImageStoreInterface $imageStoreUseCase,
        PostStoreInterface $postStoreUseCase
    ): RedirectResponse {
        $userId = $guard->user()->id;

        /** @var PostStoreRequest $request */
        $validated = $request->validated();

        // 画像を保存
        $uploadedImage = $request->file('image');
        $savedFileName = $imageStoreUseCase($userId, $uploadedImage);

        // postsにレコード追加
        $data = array_merge($validated, [
            'image' => $savedFileName,
        ]);
        $postStoreUseCase($userId, $data);

        //TODO: flash で success メッセージを出す
        $userName = Arr::get($guard->user()->user_profile, 'name');
        if ($userName) {
            return redirect()->route('post.index', ['userName' => $userName]);
        } else {
            return redirect()->route('home.index');
        }
    }
}
