<?php

declare(strict_types=1);

namespace Diviky\Security\Concerns;

use Diviky\Security\Models\LoginHistory;

trait LoginHistoryTrait
{
    /**
     * Get the entity's logins.
     */
    public function logins()
    {
        return $this->morphMany(LoginHistory::class, 'user')->latest('created_at');
    }

    /**
     * The Authentication Log notifications delivery channels.
     *
     * @return array
     */
    public function notifyNewDeviceLoginVia()
    {
        return ['mail'];
    }

    /**
     * Get the entity's last login at.
     */
    public function lastLoginAt()
    {
        return optional($this->logins()->first())->created_at;
    }

    /**
     * Get the entity's last login ip address.
     */
    public function lastLoginIp()
    {
        return optional($this->logins()->first())->ip;
    }

    /**
     * Get the entity's previous login at.
     */
    public function previousLoginAt()
    {
        return optional($this->logins()->skip(1)->first())->created_at;
    }

    /**
     * Get the entity's previous login ip.
     */
    public function previousLoginIp()
    {
        return optional($this->logins()->skip(1)->first())->ip;
    }
}
