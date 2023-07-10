<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacRoleHasPermission extends Model
{
    protected $table = 'bac_role_has_permissions';

    protected $fillable = [
        'permission_id', 'role_id',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = auth()->user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });
        static::updating(function ($model) {
            $user = auth()->user();
            if ($user) {
                $model->updated_by = $user->id;
            }

        });
    }
}
