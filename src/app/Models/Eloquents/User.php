<?php
declare(strict_types=1);

namespace App\Models\Eloquents;

use App\Models\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements UserInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param string $userName
     * @return bool
     */
    public function isMine(string $userName): bool
    {
        /** @var UserProfile $userProfile */
        $userProfile = $this->user_profile;

        if (!$userProfile) {
            return false;
        }

        return $userName === $userProfile->name;
    }

    public function user_profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
