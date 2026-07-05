<?php

namespace App\Traits;

use App\Observers\AuditableObserver;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::observe(AuditableObserver::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable')
            ->latest('created_at');
    }
}
