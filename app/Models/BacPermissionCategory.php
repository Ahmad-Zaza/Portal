<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacPermissionCategory extends Model
{
    protected $table = 'bac_permission_categories';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;
    public $with = ["permissions"];

    public function permissions()
    {
        return $this->hasMany(BacPermission::class, "permission_category_id");
    }

    public function subCategories()
    {
        return $this->hasMany(BacPermissionCategory::class, "parent_permission_category_id");
    }
}
