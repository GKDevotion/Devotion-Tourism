<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\Company;
use App\Models\Department;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public $user;
    public $is_assign_super_admin = 0;
    public $admin_id = 0;

    /**
     *
     */
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
    public function index()
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('department.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view department !');
        }

        $where = [];
        if( !$this->is_assign_super_admin ){
            $where['admin_id'] = $this->admin_id;
        }

        $industries = Industry::select('id', 'name')->orderBy('name', 'asc')->get();
        $companies = Company::select('id', 'name')->where( $where )->orderBy('name', 'asc')->get();
        return view('backend.pages.departments.index', compact( 'industries', 'companies' ));
    }

    /**
     *
     */
    public function ajaxIndex(){

        $query = Department::query();

        if( !$this->is_assign_super_admin ){
            $query->where( 'admin_id', $this->admin_id );
        }

        $query->select('id', 'name', 'admin_id', 'industry_id', 'company_id', 'admin_menu_id', 'updated_at', 'status');
        return DataTables::eloquent($query)
            ->addColumn('id', function(Department $dept) {
                return $dept->id;
            })
            ->addColumn('name', function(Department $dept) {
                return $dept->name;
            })
            ->addColumn('admin', function(Department $dept) {
                return $dept->admin->username;
            })
            ->addColumn('industry', function(Department $dept) {
                return $dept->industry->name; // Display the industry name
            })
            ->addColumn('company', function(Department $dept) {
                return $dept->company->name;
            })
            ->addColumn('url', function(Department $dept) {
                if( $dept->menu ){
                    return "<a href='".route( 'admin.'.$dept->menu->group_name.'.index' )."'>".pgTitle( $dept->menu->group_name )."</a>";
                } else {
                    return "";
                }
            })
            ->addColumn('status', function(Department $dept) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $dept->status == 0 ? 'times' : 'check').' update-status" data-status="'.$dept->status.'" data-id="'.$dept->id.'" aria-hidden="true" data-table="departments"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $dept->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$dept->id.'" data-table="departments">
                            <option value="1" '.($dept->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($dept->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('updated_at', function(Department $dept) {
                return formatDate( "Y-m-d H:i", $dept->updated_at );
            })
            ->addColumn('action', function(Department $dept ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$dept->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$dept->id.'">
                    ';

                    if ( $this->user->can('department.edit') ) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.department.edit', $dept->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ( $this->user->can('department.delete') ) {
                        $action.= '<button class="btn btn-edit text-white delete-record dropdown-item" data-id="'.$dept->id.'" data-title="'.$dept->name.'" data-segment="department">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'name', 'admin', 'industry', 'company', 'url', 'updated_at', 'status', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    if( $searchValue != "" ){
                        $query->where('name', 'like', "%{$searchValue}%")
                            ->orWhereHas('industry', function($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%");
                            });
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
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('department.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create department !');
        }

        $industries  = Industry::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'sort_order' )->get();
        $companies  = Company::select( 'id', 'industry_id', 'name' )->where( 'status', 1 )->where( 'parent_id', '>', 0 )->get();
        $adminMenus  = AdminMenu::select( 'id', 'name' )->where( 'status', 1 )->where( 'parent_id', '!=', 0 )->orderBy( 'name' )->get();
        return view('backend.pages.departments.create', compact( 'industries', 'companies', 'adminMenus' ));
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

        if (!$this->user->can('department.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create department !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'industry_id' => 'required',
            'company_id' => 'required',
            'admin_menu_id' => 'required',
        ]);


        // Create New User
        $dataObj = new Department();
        $dataObj->admin_id = $this->user->id;
        $dataObj->industry_id = $request->industry_id;
        $dataObj->company_id = $request->company_id;
        $dataObj->admin_menu_id = $request->admin_menu_id;
        $dataObj->name = $request->name;

        $comapnySlug = getField( "companies", "id", "slug", $request->company_id );
        $dataObj->slug = convertStringToSlug( $comapnySlug." ".$request->name );
        $dataObj->status = $request->status;
        $dataObj->sort_order = $request->sort_order;

        $hax_code = generateRandomHexColor();
        $dataObj->hax_code = $hax_code;
        $dataObj->rgb_code = hexToRgb( $hax_code );
        $dataObj->save();

        session()->flash('success', $dataObj->name.' has been created !!');
        return redirect()->route('admin.department.index');
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
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('department.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit department !');
        }

        $data = Department::find($id);
        $industries  = Industry::select( 'id', 'name' )->where( 'status', 1 )->get();
        $companies  = Company::select( 'id', 'industry_id', 'name' )->where( 'status', 1 )->where( 'parent_id', '>', 0 )->get();
        $adminMenus  = AdminMenu::select( 'id', 'name' )->where( 'status', 1 )->where( 'parent_id', '!=', 0 )->orderBy( 'name' )->get();
        return view('backend.pages.departments.edit', compact('data', 'industries', 'companies', 'adminMenus' ) );
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
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('department.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit department !');
        }

        $request->validate([
            'name' => 'required|max:50',
            'industry_id' => 'required',
            'company_id' => 'required',
            'admin_menu_id' => 'required',
        ]);

        // Create New User
        $dataObj = Department::find( $id );
        $dataObj->admin_id = $this->user->id;
        $dataObj->industry_id = $request->industry_id;
        $dataObj->company_id = $request->company_id;
        $dataObj->admin_menu_id = $request->admin_menu_id;
        $dataObj->name = $request->name;

        $comapnySlug = getField( "companies", "id", "slug", $request->company_id );
        $dataObj->slug = convertStringToSlug( $comapnySlug." ".$request->name );
        $dataObj->status = $request->status;
        $dataObj->sort_order = $request->sort_order;
        $dataObj->save();

        session()->flash('success', $dataObj->name.' has been updated !!');
        return redirect()->route('admin.department.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('department.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete department !');
        }

        $dataObj = Department::find($id);
        if ( $dataObj ) {
            $dataObj->delete();
            return response()->json( ['data' => ['message' => $dataObj->name.' record has been successfully deleted.'] ], 200 );
        } else {
            return response()->json( ['data' => ['message' => 'Record already deleted.'] ], 200);
        }
    }
}
