<?php
declare(strict_types=1);

namespace App\Http\Controllers\User\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Post\Interfaces\PostStoreRequestInterface;
use App\Http\Requests\User\Post\PostStoreRequest;
use App\Http\UseCases\User\Post\Interfaces\ImageStoreInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class PostStoreController extends Controller
{
    /**
     * @param Guard $guard
     * @param PostStoreRequestInterface $request
     * @param ImageStoreInterface $imageStoreUseCase
     * @return RedirectResponse
     */
    public function __invoke(
        Guard $guard,
        PostStoreRequestInterface $request,
        ImageStoreInterface $imageStoreUseCase
    ): RedirectResponse {
        $userId = $guard->user()->id;

        /** @var PostStoreRequest $request */
        $validated = $request->validated();

        $uploadedImage = $request->file('image');

        $savedFileName = $imageStoreUseCase($userId, $uploadedImage);

        $data = array_merge($validated, [
            'user_id' => $guard->id(),
            'image' => $savedFileName,
        ]);
        \Log::debug(print_r($data, true));

        //TODO: flash で success メッセージを出す
        return redirect()->route('post.create');
    }
}
