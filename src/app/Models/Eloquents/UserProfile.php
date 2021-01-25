<?php
declare(strict_types=1);

namespace App\Models\Eloquents;

use App\Models\Interfaces\UserProfileInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Eloquent implements UserProfileInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'icon',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
