<?php

namespace Diviky\Security\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth_login_history';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'logout_at' => 'datetime',
        'login_at'  => 'datetime',
    ];

    /**
     * Get the authenticatable entity that the authentication log belongs to.
     */
    public function logins()
    {
        return $this->morphTo();
    }
}
