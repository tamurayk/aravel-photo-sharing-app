<?php
declare(strict_types=1);

namespace App\Http\UseCases\User\Post;

use App\Http\UseCases\User\Post\Exceptions\PostIndexException;
use App\Http\UseCases\User\Post\Interfaces\PostIndexInterface;
use App\Models\Constants\PostConstants;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\UserProfile;
use App\Models\Interfaces\PostInterface;
use App\Models\Interfaces\UserProfileInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class PostIndex implements PostIndexInterface
{
    /** @var Post */
    private $postEloquent;

    /** @var UserProfile */
    private $userProfileEloquent;

    public function __construct(PostInterface $post, UserProfileInterface $userProfile)
    {
        $this->postEloquent = $post;
        $this->userProfileEloquent = $userProfile;
    }

    /**
     * @param string $userName
     * @param array $paginatorParam
     * @return LengthAwarePaginator
     * @throws PostIndexException
     */
    public function __invoke(
        string $userName,
        array $paginatorParam = []
    ): LengthAwarePaginator {
        $perPage = Arr::get($paginatorParam, 'perPage') ?? PostConstants::PER_PAGE;
        $orderColumn = Arr::get($paginatorParam, 'column') ?? 'created_at';
        $orderDirection = Arr::get($paginatorParam, 'direction') ?? 'desc';

        try {
            /** @var UserProfile $userProfile */
            // firstOrFail() は ModelNotFoundException を throw するが、
            // ModelNotFoundException だと 404 が返らないので、first() が null だったら 404 を返している
            $userProfile = $this->userProfileEloquent->newQuery()->where('name', $userName)->first();
            if (is_null($userProfile)) {
                throw new \Exception('The specified user name does not exist.', 404);
            }

            $userId = $userProfile->user_id;

            $query = $this->postEloquent->newQuery()
                ->where('user_id', $userId)
                ->orderBy($orderColumn, $orderDirection);

            // paginate() メソッド実行時に、HTTP Request の page クエリ文字列から対象ページを自動取得し、SQL に limit と offset が付与される
            $paginator = $query->paginate($perPage);
            return $paginator;
        } catch (\Exception $e) {
            throw new PostIndexException(
                $e->getMessage(),
                is_numeric($e->getCode()) ? $e->getCode() : 500,
                $e //Throwable $previous
            );
        }
    }
}
