<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;

    protected $table = 'account_summeries';

    protected $fillable = [
        'admin_id',
        'company_id',
        'txn_no',
        'is_check_balance',
        // Add any other fields you want to mass assign
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

    public function client_company(){
        return $this->hasOne(AccountManagememt::class, 'id', 'company_code');
    }

    public function payment(){
        return $this->hasOne(BankInformation::class, 'id', 'payment_type');
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function upload_file(){
        return $this->hasMany(AccountSummeryFileMaps::class, 'account_summery_id', 'id');
    }
}
