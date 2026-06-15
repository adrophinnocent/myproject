<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['admin_user_id', 'action', 'model_type', 'model_id', 'description', 'ip_address'];

    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public static function log(string $action, string $description, $model = null): void
    {
        static::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
