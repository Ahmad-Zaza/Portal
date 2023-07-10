<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacPermission extends Model
{
    protected $table = 'bac_permissions';

    protected $fillable = [
        'permission_category_id','name'
    ];

    public $timestamps  = true;

    public function permissionCategory(){
        return $this->belongsTo(BacPermissionCategory::class,"permission_category_id");
    }
}
