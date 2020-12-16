<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface UserInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 */

/**
 * Interface PostInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string $caption
 * @property User $user
 */
interface PostInterface extends BaseInterface
{
    public function user(): BelongsTo;
}
