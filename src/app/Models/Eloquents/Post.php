<?php
declare(strict_types=1);

namespace App\Models\Eloquents;

use App\Models\Interfaces\PostInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Eloquent implements PostInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image',
        'caption',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
