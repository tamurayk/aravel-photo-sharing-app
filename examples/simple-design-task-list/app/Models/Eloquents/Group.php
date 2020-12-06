<?php

namespace App\Models\Eloquents;

use App\Models\Interfaces\GroupInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Eloquent implements GroupInterface
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsTo
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }
}
