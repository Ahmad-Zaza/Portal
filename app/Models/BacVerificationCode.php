<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacVerificationCode extends Model
{
    protected $table = 'bac_verification_codes';
    public $timestamps  = true;
    protected $fillable = [
        "organization_id", "verification_code_period_id", "code", "license_count", "license_period", "activation_date", "expiration_date", "is_active"
    ];

    public function organization(){
        return $this->belongsTo(BacOrganization::class,'organization_id');
    }

    public function period(){
        return $this->belongsTo(BacVerificationCodePeriod::class,'verification_code_period_id');
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
