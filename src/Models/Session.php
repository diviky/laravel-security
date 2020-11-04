<?php

namespace Diviky\Security\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessions';
}
