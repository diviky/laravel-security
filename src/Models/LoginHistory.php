<?php

declare(strict_types=1);

namespace Diviky\Security\Models;

use Diviky\Bright\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use Uuids;

    const UPDATED_AT = null;

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
        'login_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * The columns should be hidden in json.
     *
     * @var array
     */
    protected $hidden = ['meta'];

    /**
     * Get the authenticatable entity that the authentication log belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function logins()
    {
        return $this->morphTo();
    }
}
