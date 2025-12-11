<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminUserGroup;
use App\Models\City;
use App\Models\Company;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Religion;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
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
    public function index( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'admin.admin', 'view') ) {
            $logArr['description'] = "Unauthorized try to view admin data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $where = [];
        if( $request->cid ){
            $where['company_id'] = _de( $request->cid );
        } else if( $request->iid ){
            $where['industry_id'] = _de( $request->iid );
        }

        $admins = Admin::with('company', 'industry')->where( $where )->get();

        $user = $this->user;

        $logArr['description'] = "Load admin data successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.admins.index', compact('admins', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "S";

        if (!fetchSinglePermission( $this->user, 'admin.admin', 'add') ) {
            $logArr['description'] = "Unauthorized try to create admin data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $groups  = AdminUserGroup::all();
        $companyArr  = Company::select('id', 'name')->where(['status' => 1])->get();
        $religionArr  = Religion::select('id', 'name')->where(['status' => 1])->get();
        $continentArr = Continent::select('id', 'name')->where( [ 'status' => 1] )->get();

        $logArr['description'] = "Admin form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.admins.create', compact('groups', 'companyArr', 'religionArr', 'continentArr'));
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

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "S";

        if (!fetchSinglePermission( $this->user, 'admin.admin', 'add') ) {
            $logArr['description'] = "Unauthorized try to store admin data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        // Validation Data
        $request->validate([
            'first_name' => 'required|max:25',
            'middle_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'username' => 'required|max:100|unique:admins',
            'email' => 'required|max:100|email|unique:admins',
            'password' => 'required|min:6',//confirmed
            'mobile_number' => 'required',
            'continent_id' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'company_id' => 'required',
        ]);

        // Create New Admin
        $admin = new Admin();
        $admin->password = Hash::make($request->password);
        $admin->first_name = $request->first_name;
        $admin->middle_name = $request->middle_name;
        $admin->last_name = $request->last_name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->mobile_number = $request->mobile_number;
        $admin->continent_id = $request->continent_id;
        $admin->country_id = $request->country_id;
        $admin->city_id = $request->city_id;
        $admin->admin_user_group_id = $request->admin_user_group_id;
        $admin->address = $request->address;
        $admin->zipcode = $request->zipcode;

        $isSuperadmin = 0;
        $admin->is_assign_super_admin = $isSuperadmin;
        $admin->company_id = $request->company_id;
        $admin->industry_id = getField( 'companies', 'id', 'industry_id', $request->company_id );
        $admin->status = 1;
        $admin->save();

        $this->mapCompanyAdmin( $request->companyids, $admin->id );

        $logArr['description'] = $admin->username.' has been created !!, give permission to access their menu';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description'] );
        return redirect('admin/permission?item_id='._en( $admin->id ));

        // return redirect()->route('admin.admin.index');
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
    public function edit( Request $request, int $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "E";

        if (!fetchSinglePermission( $this->user, 'admin.admin', 'edit') ) {
            $logArr['description'] = "Unauthorized try to edit admin data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit any admin data!');
        }

        $admin = Admin::find($id);
        $groups  = AdminUserGroup::all();
        $companyArr  = Company::select('id', 'name')->where(['status' => 1])->get();
        $religionArr  = Religion::select('id', 'name')->where(['status' => 1])->get();
        $continentArr = Continent::select('id', 'name')->where( [ 'status' => 1 ] )->get();
        $countryArr = Country::select('id', 'name')->where( [ 'status' => 1, 'continent_id' => $admin->continent_id ] )->get();
        $stateArr = State::select('id', 'name')->where( [ 'status' => 1, 'country_id' => $admin->country_id, 'continent_id' => $admin->continent_id ] )->get();
        $cityArr = City::select('id', 'name')->where( [ 'status' => 1, 'state_id' => $admin->state_id, 'continent_id' => $admin->continent_id ] )->get();

        $logArr['description'] = "Admin form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.admins.edit', compact('admin', 'groups', 'religionArr', 'continentArr', 'countryArr', 'stateArr', 'cityArr', 'companyArr'));
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
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (!fetchSinglePermission( $this->user, 'admin.admin', 'edit')) {
            $logArr['description'] = "Unauthorized try to create company account indexing";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        // Validation Data
        $request->validate([
            'email' => 'required|max:100|email|unique:admins,email,'.$id,
            'password' => 'nullable|min:6|confirmed',
            'first_name' => 'required|max:25',
            'middle_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'username' => 'required|max:100|unique:admins,username,'.$id,
            'mobile_number' => 'required',
            'continent_id' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'company_id' => 'required',
            'admin_user_group_id' => 'required'
        ]);

        // Create New Admin
        $admin = Admin::find($id);
        $admin->first_name = $request->first_name;
        $admin->middle_name = $request->middle_name;
        $admin->last_name = $request->last_name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->mobile_number = $request->mobile_number;
        $admin->continent_id = $request->continent_id;
        $admin->country_id = $request->country_id;
        $admin->city_id = $request->city_id;
        $admin->address = $request->address;
        $admin->zipcode = $request->zipcode;
        $admin->admin_user_group_id = $request->admin_user_group_id;

        $isSuperadmin = 0;
        $admin->is_assign_super_admin = $isSuperadmin;
        $admin->company_id = $request->company_id;
        $admin->industry_id = getField( 'companies', 'id', 'industry_id', $request->company_id );
        $admin->status = $request->status;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        $this->mapCompanyAdmin( $request->companyids, $admin->id );

        $logArr['description'] = 'Admin '.$request->username.' has been updated !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', );
        return redirect()->route('admin.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, int $id)
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "D";

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.admin', 'delete') ) {
            $logArr['description'] = "Unauthorized try to delete admin record";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete any data !');
        }

        $admin = Admin::find($id);
      
        $logArr['description'] = $admin->username." has been deleted successfully ";
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description'] );
        return back();
    }

    /**
     *
     */
    public function updateFieldStatus( Request $request, $table, $id, $status, $field='status' )
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        DB::table($table)->where('id', $id)->update( [$field => $status] );

        $response = [
            'success' => true,
            'data'    => "",
            'message' => $table." status ".( $status == 1 ) ? 'enabled' : 'disabled'." successfully.",
        ];

        $logArr['description'] = $response['message'];
        saveAdminLog( $logArr );// Save Access log history

        return response()->json($response, 200);
    }

    /**
     *
     */
    public function updateFieldCRM( Request $request, $table, $id, $crm)
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        DB::table($table)->where('id', $id)->update(['crm_update' => $crm]);

        $response = [
            'success' => true,
            'data'    => "",
            'message' => $table." status ".( $crm == 1 ) ? 'enabled' : 'disabled'." successfully.",
        ];

        $logArr['description'] = $response['message'];
        saveAdminLog( $logArr );// Save Access log history

        return response()->json($response, 200);
    }

    /**
     *
     */
    public function changePassword(){

        if ( is_null( $this->user )  ) {
            abort(403, 'Sorry !! You are Unauthorized to change your password !');
        }
        return view('backend.layouts.partials.change-password');
    }

    /**
     *
     */

}
