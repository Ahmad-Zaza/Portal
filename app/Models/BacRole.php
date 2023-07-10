<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacRole extends Model
{
    protected $table = 'bac_roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id', 'name', 'guard_name',
    ];

    protected $with = ["permissions"];

    public function permissions()
    {
        return $this->belongsToMany(BacPermission::class, "bac_role_has_permissions", "role_id", "permission_id");
    }

    public function users()
    {
        return $this->hasMany(BacUser::class, "role_id");
    }

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
