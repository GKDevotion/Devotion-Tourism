<?php

namespace App;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Industry;
use App\Models\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getpermissionGroups( $guard_name=null )
    {
        $pg = Permission::select('group_name as name');

        if( $guard_name ){
            $pg->where( 'guard_name', $guard_name );
        }

        $permissionGroups = $pg->groupBy('group_name')
            ->orderBy( 'name', 'ASC' )
            ->get();

        return $permissionGroups;
    }

    public static function getpermissionsByGroupName($group_name, $guard_name=null )
    {
        $p = Permission::select('name', 'id')
            ->where('group_name', $group_name);

        if( $guard_name ){
            $p->where('guard_name', $guard_name);
        }
        
        $permissions = $p->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if ( null !== $role->hasPermissionTo( $permission->name ) ) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function company_parent(){
        return $this->hasOne(Company::class, 'id', 'company_parent_id');
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function industry(){
        return $this->hasOne(Industry::class, 'id', 'industry_id');
    }

}
