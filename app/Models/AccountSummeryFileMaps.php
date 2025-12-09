<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSummeryFileMaps extends Model
{
    use HasFactory;

    protected $table = 'account_summery_file_maps';

    protected $fillable = [
        'account_summery_id',
        'company_id',
        'txn_no',
        'indexing',
        'path',
        'filename',
        'status',
        // Add other fields used in mass assignment here
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
}
