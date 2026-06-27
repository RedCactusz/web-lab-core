<?php

namespace App\Traits;

use App\Entities\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            self::audit($model, 'created');
        });

        static::updated(function (Model $model): void {
            self::audit($model, 'updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function (Model $model): void {
            self::audit($model, 'deleted', $model->getAttributes());
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model): void {
                self::audit($model, 'restored');
            });
        }
    }

    protected static function audit(Model $model, string $event, array $old = [], array $changes = []): void
    {
        $new = [];

        if ($event === 'created') {
            $new = $model->getAttributes();
        } elseif ($event === 'updated') {
            foreach ($changes as $key => $value) {
                $new[$key] = $value;
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'event' => $event,
            'old_values' => empty($old) ? null : $old,
            'new_values' => empty($new) ? null : $new,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
