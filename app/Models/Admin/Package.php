<?php

namespace App\Models\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
    'user_id',
    'website_id',
    'category_id',
    'sub_category_id',
    'title',
    'slug',
    'short_description',
    'description',
    'keyword',
    'status',
    'location',
    'adult_size',
    'tour_type',
    'duration',
    'price',
    'start_date',
    'end_date',
    'discount',
    'image',
    'card_image',
    'detail_image_1',
    'detail_image_2',
    'detail_image_3',
    'detail_image_4',
    'short_url',
    'tour_id',  // <-- ADD THIS
];
    use HasFactory;

    // public function author(){
    //     return $this->hasOne( User::class, 'id', 'user_id' );
    // }

    public function category(){
        return $this->hasOne( Categories::class, 'id', 'category_id' );//->where( 'status', 1 );
    }

    public function sub_category(){
        return $this->hasOne( Categories::class, 'id', 'sub_category_id' );
    }


    // public function author(){
    //     return $this->hasOne( User::class, 'id', 'user_id' );
    // }
}
