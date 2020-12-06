<?php

namespace App\Models\Eloquents;

use App\Models\Interfaces\AdministratorInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Administrator extends Authenticatable implements AdministratorInterface
{
    protected $table = 'administrators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * @return HasOne
     */
    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }
}
