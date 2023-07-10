<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;

class BacUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'bac_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id', 'role_id', 'microsoft365_user_guid', 'first_name', 'last_name', 'email','upn', 'phone', 'timezone', 'microsoft365_session_guid',
        'is_super_admin',"status",'last_login_date','registration_date','role_assigned_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $with = ["organization", "role"];

    public function organization()
    {
        return $this->belongsTo(BacOrganization::class, 'organization_id');
    }

    public function bac_role()
    {
        return $this->belongsTo(BacRole::class, 'role_id');
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function checkPermission($permission)
    {
        $permissions = auth()->user()->permissions;
        if ($permissions) {
            if (in_array($permission, $permissions)) {
                return true;
            }

        }
        return false;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $user = auth()->user();
            if($user){
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });
        static::updating(function ($model) {
            $user = auth()->user();
            if($user)
                $model->updated_by = $user->id;
        });
    }
}
