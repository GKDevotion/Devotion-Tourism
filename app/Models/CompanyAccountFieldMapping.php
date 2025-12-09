<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAccountFieldMapping extends Model
{
    use HasFactory;

    protected $table = 'company_account_mgt_field_maps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'account_mgt_field_id', 'status',
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

    public function account_field(){
        return $this->hasOne(AccountField::class, 'id', 'account_mgt_field_id');
    }
}
