<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (!fetchSinglePermission( $this->user, 'admin.permission', 'view') ) {
            $logArr['description'] = "Unauthorized try to view any permission";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view any permissions !');
        }

        $id = $request->item_id ? _de( $request->item_id ) : 0;
        $where = "";
        if( !empty( $id ) )
            $where = "a.id = ".$id." AND";

        $dataArr = DB::select("SELECT m.class_name AS am_name, m.name, m.parent_id  AS parent_id , m.id AS admin_menu_id, a.id AS admin_user_id, CONCAT(a.first_name,' ', a.last_name) AS admin_user_firstname, g.name AS admin_user_group_name
								FROM admin_menus m ,admins a
                                left JOIN admin_user_groups g ON g.id = a.admin_user_group_id
								WHERE ".$where." m.parent_id = 0 AND m.status = 1
								GROUP BY a.id, m.id
                                ORDER BY a.id, m.id");

        $logArr['description'] = "Permission loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.permissions.create', compact('dataArr', 'id' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $roles  = Role::all();
        return view('backend.pages.permissions.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins',
            'username' => 'required|max:100|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create New Admin
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been created !!');
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        $admin = Admin::find($id);
        $roles  = Role::all();
        return view('backend.pages.permissions.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        session()->flash('success', '');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        // if ($id === 1) {
        //     session()->flash('error', 'Sorry !! You are not authorized to delete this Admin as this is the Super Admin. Please create new one if you need to test !');
        //     return back();
        // }

        // $admin = Admin::find($id);
        // if (!is_null($admin)) {
        //     $admin->delete();
        // }

        // session()->flash('success', 'Admin has been deleted !!');

        return response()->json( ['data' => ['message' => 'Record has been successfully deleted.' ] ], 200);
    }

    /*
     +-----------------------------------------+
     Function will update or insert permissions for particular admin user
     will be in post method.
     +-----------------------------------------+
     */
    public function changePermission( Request $request )
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.permission', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update any admin permission";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to update any admin permission !');
        }

        $val =  $request['val'];
        $type = $request['type'];

        $valArr = explode("||",$val);
        $data = array();
        $sql="";

        foreach($valArr as $k=>$ar)
        {
            $arArr = explode("|",$ar);

            $data['admin_user_id'] = $arArr[1];
            $data['admin_menu_id'] = $arArr[2];

            if($type == "all" || $type == "allall")
            {
                $data['permission_view'] = $arArr[3];
                $data['permission_add'] = $arArr[3];
                $data['permission_edit'] = $arArr[3];
                $data['permission_delete'] = $arArr[3];
            }
            else if($type == "viewall" || $type == "view")
                $data['permission_view'] = $arArr[3];
            else if($type == "addall" || $type == "add")
                $data['permission_add'] = $arArr[3];
            else if($type == "editall" || $type == "edit")
                $data['permission_edit'] = $arArr[3];
            else if($type == "deleteall" || $type == "delete")
                $data['permission_delete'] = $arArr[3];

            $key = [];
            $values = [];
            $update = "";
            foreach($data as $k=>$v )
            {
                $key[] = $k;
                $values[] = $v;
                $update .= $k."=".$v.", ";
            }

            $update .= "updated_at=NOW()";

            $sql = "INSERT INTO base_permissions ( ".implode( ",", $key )." )
                    VALUES ( ".implode( ",", $values )." )
                    ON DUPLICATE KEY UPDATE ".$update;

            DB::insert( $sql );

        }

        $logArr['description'] = "update admin permission successfully";
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( [ "type" => "success", "msg" => "Records has been updated successfully."]);
    }
}
