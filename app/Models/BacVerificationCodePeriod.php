<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacVerificationCodePeriod extends Model
{
    protected $table = 'bac_verification_code_periods';
    public $timestamps  = true;
    protected $fillable = [
        "name", "code"
    ];

}
