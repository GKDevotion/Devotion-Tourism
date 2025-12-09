<?php
/**
 * @package pr_: prmsn_hlp
 * @author Devotion Tech Team
 * @version 1.9
 * @abstract admin features helper
 * @copyright Devotion Tech
 */

use App\Models\BasePermission;

/**
 * child segments handle permission
 * @param string $controller
 * @param string $permission
 * @return unknown|number
 */
function fetchSinglePermission( $user, $controller="" , $permission = "view")
{
    $admin_id = $user->id;

    $permissionObj = BasePermission::join('admin_menus', 'admin_menus.id', '=', 'base_permissions.admin_menu_id')
    ->select('base_permissions.id')
    ->where( [
        'base_permissions.admin_user_id' => $admin_id,
        'admin_menus.class_name' => $controller,
        'permission_'.$permission => 1
    ] )
    ->first();

    if( $permissionObj ) {
        return true;
    } else {
        return false;
    }
}
