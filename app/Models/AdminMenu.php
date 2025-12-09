<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use HasFactory;
    // protected $table = 'admin_menus';
    // protected $guarded = array();

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = Carbon::now('Asia/Dubai');
            $model->updated_at = Carbon::now('Asia/Dubai');
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now('Asia/Dubai');
        });
    }

    public function parentMenu(){
        return $this->hasOne( AdminMenu::class, 'id', 'parent_id');
    }
}
