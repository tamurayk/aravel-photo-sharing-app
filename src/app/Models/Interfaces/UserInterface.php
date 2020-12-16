<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property Collection|Post[]\null $posts
 */
interface UserInterface extends BaseInterface
{
    public function posts(): HasMany;
}
