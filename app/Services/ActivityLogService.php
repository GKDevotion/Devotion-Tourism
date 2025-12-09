<?php
namespace App\Services;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Route;

class ActivityLogService
{
    public static function log( int $admin_id, int $user_id, string $action, array $tblDetails = [])
    {
        $adminLogObj = new AdminLog();
        $adminLogObj->admin_id = $admin_id;
        $adminLogObj->user_id = $user_id;
        $adminLogObj->class_name = Route::current()->action['as'] ?? 'admin.client.store';
        $adminLogObj->item_name = $tblDetails['item_name'];
        $adminLogObj->group_name = $tblDetails['group_name'];
        $adminLogObj->table_name = $tblDetails['table_name'];
        $adminLogObj->table_field = $tblDetails['table_field'];
        $adminLogObj->primary_id = $tblDetails['primaryId'];
        $adminLogObj->parent_table_pk_id = $tblDetails['parent_table_pk_id'];
        $adminLogObj->description = $tblDetails['description'];
        $adminLogObj->action = $action;
        $adminLogObj->log_ip = request()->ip();
        $adminLogObj->save();
    }
}