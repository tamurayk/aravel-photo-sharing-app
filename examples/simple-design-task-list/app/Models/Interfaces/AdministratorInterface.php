<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Interface UserInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Collection|Group|null $group
 */
interface AdministratorInterface extends BaseInterface
{
    public function group(): HasOne;
}
