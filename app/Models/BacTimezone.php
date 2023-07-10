<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacTimezone extends Model
{
    protected $table = 'bac_timezones';

    protected $fillable = [
        'name','is_default'
    ];

    public $timestamps  = false;
}
