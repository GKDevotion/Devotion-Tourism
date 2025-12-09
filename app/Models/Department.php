<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

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

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function industry(){
        return $this->hasOne(Industry::class, 'id', 'industry_id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id')->where( 'status', 1 );
    }

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id', 'id')->where( 'status', 1 );
    }

    public function menu()
    {
        return $this->belongsTo(AdminMenu::class, 'admin_menu_id', 'id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }
}
