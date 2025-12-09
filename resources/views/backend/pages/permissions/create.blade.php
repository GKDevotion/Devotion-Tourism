<?php
use Illuminate\Support\Facades\DB;
$userVal = array();
// $id = 0;
?>

@extends('backend.layouts.master')

@section('title')
Permission Create - Admin Panel
@endsection

@section('styles')
<style>
    .admin_permission .box > .content { padding: 10px; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC; min-height: 300px; overflow: auto; }
    .admin_permission form { margin: 0; padding: 0; clear: both; }
    .admin_permission .vtabs { border-right: 1px solid #DDDDDD; }
    .admin_permission .vtabs a.selected { padding-right: 15px; color: #ab8134;}/*  background: #FFFFFF; */
    .admin_permission .list { border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px; }
    .admin_permission .list .left { text-align: left; padding: 5px; }
    .admin_permission .list thead th a, .list thead th { border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; padding: 0px 5px; text-decoration: none; color: #222222; font-weight: bold; }
    .admin_permission input { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
    .admin_permission .list tbody td { vertical-align: middle; padding: 0px 5px; background: #FFFFFF; }
    .admin_permission .list td { border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; }
    .admin_permission td { font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #000000; }
    .admin_permission .list label { cursor: pointer; }
    .notification { position: relative; margin: 0px 0px 15px 0px; padding: 0; border: 1px solid; background-position: 10px 11px !important; background-repeat: no-repeat !important; font-size: 13px; width: 95.8%; }
    .notification .close { color:#990000; font-size:9px; position:absolute; right:5px; top:5px; }
    .admin_permission .tabs-left, .tabs-right { border-bottom: none; padding-top: 2px; }
    /* .admin_permission .tabs-left { border-right: 1px solid #ddd; } */
    .admin_permission .tabs-right { border-left: 1px solid #ddd; }
    .admin_permission .tabs-left>li, .tabs-right>li { float: none; margin-bottom: 2px; width: 100%; padding: 5px; }
    .admin_permission .tabs-left>li { margin-right: -1px; }
    .admin_permission .tabs-right>li { margin-left: -1px; }
    .admin_permission .tabs-left>li.active>a,
    .admin_permission .tabs-left>li.active>a:hover,
    .admin_permission .tabs-left>li.active>a:focus { /* border-bottom-color: #ddd; */ border-right-color: transparent; }
    .admin_permission .tabs-right>li.active>a,
    .admin_permission .tabs-right>li.active>a:hover,
    .admin_permission .tabs-right>li.active>a:focus { border-bottom: 1px solid #ddd; border-left-color: transparent; }
    .admin_permission .tabs-left>li>a { border-radius: 4px 0 0 4px; margin-right: 0; display:block; }
    .admin_permission .tabs-right>li>a { border-radius: 0 4px 4px 0; margin-right: 0; }
    .admin_permission .vertical-text { margin-top:50px; border: none; position: relative; }
    .admin_permission .vertical-text>li { height: 20px; width: 120px; margin-bottom: 100px; }
    .admin_permission .vertical-text>li>a { border-bottom: 1px solid #ddd; border-right-color: transparent; text-align: center; border-radius: 4px 4px 0px 0px; }
    .admin_permission .vertical-text>li.active>a,
    .admin_permission .vertical-text>li.active>a:hover,
    .admin_permission .vertical-text>li.active>a:focus { border-bottom-color: transparent; border-right-color: #ddd; border-left-color: #ddd; }
    .admin_permission .vertical-text.tabs-left { left: -50px; }
    .admin_permission .vertical-text.tabs-right { right: -50px; }
    .admin_permission .vertical-text.tabs-right>li { -webkit-transform: rotate(90deg); -moz-transform: rotate(90deg); -ms-transform: rotate(90deg); -o-transform: rotate(90deg); transform: rotate(90deg); }
    .admin_permission .vertical-text.tabs-left>li { -webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg); -ms-transform: rotate(-90deg); -o-transform: rotate(-90deg); transform: rotate(-90deg); }
    .admin_permission .tabs-left>li {
        /* color: #555; */
        cursor: default;
        /* background-color: #fff; */
        border: 1px solid #ddd;
    }
    .admin_permission .tabs-left>li:hover {
        padding-left: 10px;
        font-size: 14px;
    }
    .listingPreloader { width:100%; height:100%; background:url('{{url("public_html/images/preloader.gif")}}') no-repeat center center; left:0; top:0; position:absolute; }

    .box_4 {
        background: #eee;
    }

    .switch_box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: inline-flex;
        max-width: 50px;
        min-width: 50px;
        height: 26px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .input_wrapper {
        width: 50px;
        height: 26px;
        position: relative;
        cursor: pointer;
    }
    .input_wrapper input[type="checkbox"] {
        width: 50px;
        height: 26px;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: #ff4b5c;
        border-radius: 2px;
        position: relative;
        outline: 0;
        -webkit-transition: all .2s;
        transition: all .2s;
    }
    input[type="checkbox"], input[type="radio"] {
        box-sizing: border-box;
        padding: 0;
    }

    .input_wrapper input[type="checkbox"]:after {
        position: absolute;
        content: "";
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background: #dfeaec;
        z-index: 2;
        border-radius: 2px;
        -webkit-transition: all .35s;
        transition: all .35s;
    }
    .input_wrapper .is_checked {
        width: 12px;
        top: 50%;
        left: 18%;
        -webkit-transform: translateX(190%) translateY(-30%) scale(0);
        transform: translateX(190%) translateY(-30%) scale(0);
    }
    .input_wrapper input[type="checkbox"]:checked {
        background: #4caf50;
    }
    label {
        display: inline-block;
        margin: 0.5rem;
    }
    .input_wrapper svg {
        position: absolute;
        top: 50%;
        -webkit-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
        fill: #fff;
        -webkit-transition: all .35s;
        transition: all .35s;
        z-index: 1;
    }
    svg {
        overflow: hidden;
        vertical-align: middle;
    }
    .input_wrapper .is_unchecked {
        width: 10px;
        top: 50%;
        right: 10%;
        -webkit-transform: translateX(0) translateY(-30%) scale(1);
        transform: translateX(0) translateY(-50%) scale(1);
    }

    .tabs-left li.active{
        background-color: #ab8134;
        border-radius: 0 25px 25px 0;
    }

    .admin_permission .tabs-left>li.active>a{
        color: #fff;
    }
 </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-2">
            @include('backend.layouts.partials.side-bar-logo')
        </div>
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Permission Create</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.permission.index') }}">All Permissions</a></li>
                    <li><span>Create Admin</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('permission.create'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                @endif
                <a href="{{ route('admin.permission.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        <div class="col-md-1">
            <span class="text-theme">
                <i class="fa fa-user"></i>
                {{auth()->guard('admin')->user()->username}}
            </span>
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div id="tab-option">
                        <form id="form" class="admin_permission" enctype="multipart/form-data" method="post">
                            <?php
                            $name = "General";
                            if( !empty( $id ) )
                                $name = getField( 'admins' , 'id', 'first_name', $id );
                            ?>
                            <h3 class="mb-3"><b>{{$name}}</b> Permission</h3>
                            <div class="row">
                                <div id="vtab-option" class="col-md-3 vtabs <?php echo ( $id ) ? 'd-none' : '';?> ">
                                    <ul class="nav nav-tabs tabs-left">
                                       <?php
                                        $admin_user_id = 0;
                                        if ( count($dataArr) > 0)
                                        {
                                            foreach ($dataArr as $k => $ar)
                                            {
                                                if ($admin_user_id == $ar->admin_user_id)
                                                    continue;
                                                ?>
                                                  <li class="<?php echo ( $k == 0 ) ? 'active' : '';?>">
                                                      <a href="#tab-option-<?php echo $k; ?>" class="tablinks selected" data-toggle="tab">
                                                          <?php echo $ar->admin_user_firstname; ?>
                                                      </a>
                                                  </li>
                                                <?php
                                                $admin_user_id = $ar->admin_user_id;
                                            }
                                        } ?>
                                    </ul>
                                </div>
                                <div class="col-md-<?php echo ( $id ) ? '12' : '9';?> ">
                                    <div class="tab-content p-0">
                                        <?php displayMenu($dataArr, $id); ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

<?php
    /*
    * +------------------------------------------------------------------------+
    * display menu for particular user
    * @author Cloudwebs
    * @param $res database data
    * @param $admin_user_id
    * @key key of array just to use for store in div id
    * +------------------------------------------------------------------------+
    */
    function displayMenu($res, $id )
    {
        $admin_user_id = 0;
        foreach ($res as $key => $val)
        {
            if ($admin_user_id == $val->admin_user_id)
                continue;
            ?>
            <div id="tab-option-<?php echo $key; ?>" class="tab-pane vtabs-content <?php echo ( $key == 0 ) ? 'active' : '';?>">
                <table class="list fixed_header" style="width: 90%">
                    <thead>
                        <tr>
                                <th class="text-center p-2" width="25%">Menus</th>
                                <th class="text-center p-2" width="15%">View</th>
                                <th class="text-center p-2" width="15%">Add</th>
                                <th class="text-center p-2" width="15%">Edit</th>
                                <th class="text-center p-2" width="15%">Delete</th>
                                <th class="text-center p-2" width="15%">All</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($res as $k => $ar)
                    {
                        if ($ar->admin_user_id != $val->admin_user_id)
                            continue;
                        ?>
                        <tbody>
                            <tr>
                                <td class="left" colspan="6">
                                    <b><?php echo $ar->name; ?></b>
                                </td>
                            </tr>
                            <?php displaySubMenu($ar->admin_menu_id, $ar->admin_user_id, null, "&raquo "); ?>
                        </tbody>
                        <?php
                    }
                    if( $id ){?>
                        <tfoot>
                            <tr>
                                <td class="text-center p-3" colspan="6">
                                    <?php
                                    $string = url()->previous();
                                    $plorp = substr(strrchr($string,'/'), 1);
                                    $string = substr($string, 0, - strlen($plorp));
                                    ?>
                                    <a href="{{rtrim( $string, "/")}}" class="btn btn-success">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                        Submit
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    <?php }?>
                </table>
            </div>
            <?php
            $admin_user_id = $val->admin_user_id;
            $userVal[] = $val->admin_user_id;
        }
    }

    /*
        * +------------------------------------------------------------------------+
        * recursively display sub menu for particular menu
        * @author Cloudwebs
        * @param $res database data
        * @param $admin_user_id
        * @key key of array just to use for store in div id
        * +------------------------------------------------------------------------+
        */
    function displaySubMenu($admin_menu_id, $admin_user_id, $result, $level)
    {
        if (isset($result) && sizeof($result) > 0)
        {
            foreach ($result as  $row)
            {
                $cnt = getField("admin_menus","id","parent_id",$row->id);
                displayPermission($row, $row->id, $admin_user_id, ((int) $cnt > 0 ? '<b>' : '') . $level);
                if ((int) $cnt > 0)
                {
                    displaySubMenu($row->id, $admin_user_id, null, $level . "&raquo; ");
                }
            }
        }
        else
        {
            $result = DB::select("SELECT * FROM admin_menus WHERE parent_id=".$admin_menu_id." AND id != 7");//." AND status = 1"
            if ( !empty($result))
            {
                displaySubMenu($admin_menu_id, $admin_user_id, $result, $level);
            }
        }
    }

    /*
        * +------------------------------------------------------------------------+
        * recursively display menu permission for particular user
        * @author Cloudwebs
        * @param $res database data
        * @param $admin_user_id
        * @key key of array just to use for store in div id
        * +------------------------------------------------------------------------+
        */
    function displayPermission($row, $admin_menu_id, $admin_user_id, $level)
    {
        $result = DB::select("SELECT * FROM base_permissions WHERE admin_menu_id=".$row->id." AND admin_user_id=".$admin_user_id." ");
        if (! empty($result))
        {
        ?>
            <tr class="user-<?php echo $admin_user_id; ?>-tr" data-="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>">
                <td class="left"><?php echo $level.$row->name; ?></td>
                <td class="text-center">
                    <label class="permission-container container-checkbox d-flex align-items-center justify-content-center my-1">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="view" value="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> v menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]->permission_view == 1 )? ' checked="true" ':'';?> >
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox d-flex align-items-center justify-content-center my-1">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="add" value="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> a menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]->permission_add == 1 )? ' checked="checked" ':'';?> >
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox d-flex align-items-center justify-content-center my-1">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="edit" value="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> e menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]->permission_edit == 1 )? ' checked="checked" ':'';?> >
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox d-flex align-items-center justify-content-center my-1">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="delete" value="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> d menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]->permission_delete == 1 )? ' checked="checked" ':'';?> >
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox d-flex align-items-center justify-content-center my-1">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="all" value="<?php echo $result[0]->id."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]->permission_view == 1 && $result[0]->permission_add == 1 && $result[0]->permission_edit == 1 && $result[0]->permission_delete == 1 )? ' checked="checked" ':'';?> >
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
            </tr>
        <?php
        }else{
            ?>
            <tr class="user-<?php echo $admin_user_id; ?>-tr" data-="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>">
                <td class="left"><?php echo $level.$row->name; ?></td>
                <td class="text-center">
                    <label class="permission-container container-checkbox">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="view" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> v menu-<?php echo $admin_menu_id; ?>">
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="add" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> a menu-<?php echo $admin_menu_id; ?>">
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="edit" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> e menu-<?php echo $admin_menu_id; ?>">
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="delete" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> d menu-<?php echo $admin_menu_id; ?>">
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
                <td class="text-center">
                    <label class="permission-container container-checkbox">
                        <div class="switch_box box_4">
                            <div class="input_wrapper ml-2">
                                <input type="checkbox" name="all" class="active_deactive_tgl_btn switch_4 user-<?php echo $admin_user_id; ?> menu-<?php echo $admin_menu_id; ?>">
                                <svg class="is_checked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.67 426.67">
                                    <path d="M153.504 366.84c-8.657 0-17.323-3.303-23.927-9.912L9.914 237.265c-13.218-13.218-13.218-34.645 0-47.863 13.218-13.218 34.645-13.218 47.863 0l95.727 95.727 215.39-215.387c13.218-13.214 34.65-13.218 47.86 0 13.22 13.218 13.22 34.65 0 47.863L177.435 356.928c-6.61 6.605-15.27 9.91-23.932 9.91z"/>
                                </svg>
                                <svg class="is_unchecked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982">
                                    <path d="M131.804 106.49l75.936-75.935c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.49 81.18 30.555 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.99 6.99-6.99 18.323 0 25.312L81.18 106.49 5.24 182.427c-6.99 6.99-6.99 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0L106.49 131.8l75.938 75.937c6.99 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.323 0-25.313l-75.936-75.936z" fill-rule="evenodd" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </td>
            </tr>
        <?php
        }
    }

?>
@section('scripts')

<script>
    var userArr = '<?php echo json_encode($userVal); ?>';
</script>
<script src="{{ asset('public/backend/assets/js/permission.js')}}"></script>
@endsection
