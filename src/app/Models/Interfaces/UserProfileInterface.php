<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface UserProfileInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property User $user
 */
interface UserProfileInterface extends BaseInterface
{
    public function user(): BelongsTo;
}
