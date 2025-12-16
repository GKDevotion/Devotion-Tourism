<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageImageMap extends Model
{
    use HasFactory;
    protected $table = 'package_image_map';
    // protected $guarded = array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tour_id',
        'filename',
        'status',
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

    // public function parent(){
    //     return $this->hasOne( PropertyFeature::class, 'id', 'parent_id');
    // }
}
