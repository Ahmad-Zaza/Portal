<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacRestoreHistoryDetail extends Model
{
    public $table = 'bac_restore_history_details';
    public $timestamps  = true;
    protected $fillable = ['restore_history_id', 'item_id', 'item_name', 'item_parent_id','item_parent_name', 'status', 'duration','returned_response','error_response','exported_file_name','exported_file_size','last_download_date'];

    public function restore_history(){
        return $this->belongsTo(BacRestoreHistory::class,'restore_history_id');
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
