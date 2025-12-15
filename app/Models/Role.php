<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasRoles;
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = [
        'name', 'guard_name'
    ];
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
    // In your Role model
public function permissions()
{
    return $this->belongsToMany(Permission::class, 'role_has_permissions');
    // Adjust table name if different
}
}
