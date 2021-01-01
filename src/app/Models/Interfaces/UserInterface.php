<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\Post;
use App\Models\Eloquents\UserProfile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Interface UserInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property UserProfile|null $user_profile
 * @property Collection|Post[]\null $posts
 */
interface UserInterface extends BaseInterface
{
    public function user_profile(): HasOne;

    public function posts(): HasMany;
}
