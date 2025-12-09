<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountManagememt extends Model
{
    use HasFactory;

    protected $table = 'account_managements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'code', 'name', 'status',
    ];

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

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }
}
