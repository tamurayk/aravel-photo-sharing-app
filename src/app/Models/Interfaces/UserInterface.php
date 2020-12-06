<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use Carbon\Carbon;

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
interface UserInterface extends BaseInterface
{
}
