<?php
declare(strict_types=1);

namespace App\Models\Eloquents;

use App\Models\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Eloquent implements TaskInterface
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
