<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMeeting extends Model
{
    use HasFactory;

    protected $table = "company_meetings";

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
        return $this->hasOne(User::class, 'id', 'admin_id');
    }

    public function communication_type(){
        return $this->hasOne(CommunicationType::class, 'id', 'communication_type_id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
