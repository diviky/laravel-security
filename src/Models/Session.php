<?php

declare(strict_types=1);

namespace Diviky\Security\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
