<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Industry;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{

    public $user;
    public $is_assign_super_admin = 0;
    public $admin_id = 0;
    public $user_type = 1; //1: User, 2: Employee, 3: Customer

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     *
     */
    public function setPublicVar(){
        $this->is_assign_super_admin = $this->user->is_assign_super_admin;
        $this->admin_id = $this->user->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if (is_null($this->user) || !$this->user->can('user.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view User !');
        }

        return view('backend.pages.users.index', compact( 'request' ) );
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $this->setPublicVar();

        $query = User::query();

        if( $request->cid ){
            $query->where( 'company_id', _de( $request->cid ) );
        } else if( $request->iid ){
            $query->where( 'industry_id', _de( $request->iid ) );
        }

        if( !$this->is_assign_super_admin ){
            $query->where( 'admin_id', $this->admin_id );
        }

        $query->where( 'type', $this->user_type );

        return DataTables::eloquent($query)
            ->addColumn('id', function(User $mr) {
                return $mr->id;
            })
            ->addColumn('name', function(User $mr) {
                return $mr->name;
            })
            ->addColumn('email', function(User $mr) {
                return $mr->email;
            })
            ->addColumn('admin', function(User $mr) {
                return $mr->admin->username;
            })
            ->addColumn('industry', function(User $mr) {
                return $mr->industry->name;
            })
            ->addColumn('company', function(User $mr) {
                return $mr->company->name;
            })
            ->addColumn('company_parent', function(User $mr) {
                return $mr->company_parent->name;
            })
            ->addColumn('status', function(User $mr) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $mr->status == 0 ? 'times' : 'check').' update-status" data-status="'.$mr->status.'" data-id="'.$mr->id.'" aria-hidden="true" data-table="users"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $mr->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$mr->id.'" data-table="users">
                            <option value="1" '.($mr->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($mr->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('created_at', function(User $mr) {
                return formatDate( "Y-m-d H:i", $mr->created_at );
            })
            ->addColumn('updated_at', function(User $mr) {
                return formatDate( "Y-m-d H:i", $mr->updated_at );
            })
            ->addColumn('action', function(User $mr ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$mr->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$mr->id.'">
                    ';

                    if ($this->user->can('user.edit')) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.user.edit', $mr->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($this->user->can('city.edit')) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$mr->id.'" data-title="'.$mr->name.'" data-segment="users">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'name', 'email', 'admin', 'company', 'company_parent', 'industry', 'created_at', 'updated_at', 'status', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    if( $searchValue != "" ){
                        $query->where('name', 'like', "%{$searchValue}%")
                            ->orWhereHas('industry', function($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%");
                            })
                            ->orWhereHas('company', function($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%");
                            });
                            // ->orWhere('email', 'like', "%{$searchValue}%");
                        }
                }
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request('order')[0]['column'];
                    $orderDirection = request('order')[0]['dir'];
                    $columns = request('columns');
                    $query->orderBy($columns[$orderColumn]['data'], $orderDirection);
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('user.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create user !');
        }

        $industries  = Industry::select( 'id', 'name' )->where( 'status', 1 )->get();
        $parent_companies  = Company::select( 'id', 'parent_id', 'name' )->where( ['status' => 1, 'parent_id' => 0 ] )->get();
        $companies  = Company::select( 'id', 'parent_id', 'name' )->where( 'status', 1 )->where( 'parent_id', '>', 0 )->get();
        $roles  = Role::where( 'status', 1 )->get();
        return view('backend.pages.users.create', compact('roles', 'industries', 'parent_companies', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('user.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create User !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'industry_id' => 'required',
            'company_id' => 'required',
            'company_parent_id' => 'required',
            'email' => 'required|max:100|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create New User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->admin_id = $this->user->id;
        $user->industry_id = $request->industry_id;
        $user->company_id = $request->company_id;
        $user->company_parent_id = $request->company_parent_id;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->type = $this->user_type;
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        session()->flash('success', $request->name.' has been created !!');
        return redirect()->route('admin.user.index');
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
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('user.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit User !');
        }

        $user = User::find($id);
        $industries  = Industry::select( 'id', 'name' )->where( 'status', 1 )->get();
        $parent_companies  = Company::select( 'id', 'parent_id', 'name' )->where( ['status' => 1, 'parent_id' => 0 ] )->get();
        $companies  = Company::select( 'id', 'parent_id', 'name' )->where( 'status', 1 )->where( 'parent_id', '>', 0 )->get();
        $roles  = Role::where( 'status', 1 )->get();
        return view('backend.pages.users.edit', compact('user', 'roles', 'companies', 'parent_companies', 'industries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('user.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit user !');
        }

        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'company_id' => 'required',
            'industry_id' => 'required',
            'company_parent_id' => 'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->admin_id = $this->user->id;
        $user->industry_id = $request->industry_id;
        $user->company_id = $request->company_id;
        $user->company_parent_id = $request->company_parent_id;
        $user->status = $request->status;
        $user->type = $this->user_type;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        // session()->flash('success', 'User has been updated !!');
        session()->flash('success', $request->name.' has been updated !!');
        return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('user.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete user !');
        }

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        // session()->flash('success', 'User has been deleted !!');
        return response()->json( ['data' => ['message' => "'".$user->name.'" has been successfully deleted.' ] ], 200);
    }
}
