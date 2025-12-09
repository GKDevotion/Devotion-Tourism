<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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

    // public function user(){
    //     return $this->hasOne(User::class, 'id', 'user_id');
    // }

    public function industry(){
        return $this->hasOne(Industry::class, 'id', 'industry_id');
    }

    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id')->where( 'status', 1 );
    }

    public function bank(){
        return $this->hasMany(BankInformation::class, 'company_id', 'id');
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

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function adminmap(){
        return $this->hasMany(CompanyAdminMap::class, 'company_id', 'id')->where('status', 1);
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }
}
