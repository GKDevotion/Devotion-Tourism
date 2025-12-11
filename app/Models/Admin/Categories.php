<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    // public function type(){
    //     return $this->hasone(Type::class, 'id', 'type_id');
    // }

    public function sub_category(){
        return $this->hasMany( Categories::class, 'id', 'parent_id' );
    }


    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id')->where( 'status', 1 );
    }

    public function SingleChildren()
    {
        return $this->hasOne(Categories::class, 'id', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id', 'id')->where( 'status', 1 );
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    // public function getPackageViaCategory(){
    //     return $this->hasMany(Package::class, 'sub_category_id')->where( 'status', 1 )->limit(8);
    // }
}
