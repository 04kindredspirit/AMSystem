<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        $name = property_exists($model, 'logName') ? $model::$logName : $model->getTable();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => ucfirst($action) . ' ' . $name,
        ]);
    }
}
