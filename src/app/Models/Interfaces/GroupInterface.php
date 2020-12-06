<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\Administrator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface GroupInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property string $name
 * @property Collection|Administrator|null $administrator
 */
interface GroupInterface extends BaseInterface
{
    public function administrator(): BelongsTo;
}
