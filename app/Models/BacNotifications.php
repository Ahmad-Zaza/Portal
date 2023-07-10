<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacNotifications extends Model
{
    protected $table = 'bac_notifications';

    protected $fillable = [
        'code','text'
    ];

    public $timestamps  = true;

    public function organizationNotifications(){
        return $this->hasMany(BacOrganizationNotifications::class,'notification_id');
    }
}
