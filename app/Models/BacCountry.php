<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacCountry extends Model
{
    protected $table = 'bac_countries';

    protected $fillable = [
        'name', 'code'
    ];
}
