<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminLogController extends Controller
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

        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'admin-log', 'view')) {
            $logArr['description'] = "Unauthorized try to view log history";

            abort(403, 'Sorry !! You are Unauthorized to view Log History !');
        }

        $logArr['description'] = "Load log history data successfully";

        $user = Admin::select( 'id', 'username' )->where('status', 1)->get();

        return view('backend.pages.admin-log.index', compact( 'request', 'user' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $view = fetchSinglePermission( $this->user, 'admin-log', 'view');

        $actionArr = [
            'V' => 'View',
            'S' => 'Store',
            'U' => 'Update',
            'D' => 'Delete',
            'C' => 'Create',
            'E' => 'Edit',
            'EX' => 'Exception Error',
            'L' => 'Login'
        ];

        // startQueryLog();
        $query = AdminLog::query();

        if (!empty($request->uid)) {
            $query->where('admin_id', $request->uid);
        }

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        } elseif (!empty($request->from_date)) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::now()->endOfDay()
            ]);
        }

        $query->orderBy( 'id', 'DESC' );
        // displayQueryResult();die;
        return DataTables::eloquent($query)
            ->addColumn('id', function(AdminLog $obj) {
                return $obj->id;
            })
            ->addColumn('name', function(AdminLog $obj) {
                return $obj->admin->username;
            })
            ->addColumn('information', function(AdminLog $obj) use( $actionArr ) {
                return $actionArr[$obj->action];
            })
            ->addColumn('ip_address', function(AdminLog $obj) {
                return $obj->ip_address;
            })
            ->addColumn('description', function(AdminLog $obj) {
                return $obj->description;
            })
            ->addColumn('created_at', function(AdminLog $obj) {
                return formatDate( "Y-m-d H:i", $obj->created_at );
            })
            ->addColumn('action', function(AdminLog $obj) use ( $view ) {

                if( $view && $obj->table_view != "" ){
                    return '<i class="fa fa-eye btn btn-edit show-difference" data-toggle="modal" data-target="#differentDescriptionModal" data-id="'.$obj->id.'"></i>';
                }

                return '-';
            })
            ->rawColumns(['id', 'name', 'action', 'ip_address', 'description', 'created_at'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];

                    $query->where(function($q) use ($searchValue) {
                        $q->whereHas('admin', function($q2) use ($searchValue) {
                            $q2->where('username', 'like', "%{$searchValue}%");
                        })->orWhere('description', 'like', "%{$searchValue}%")
                        ->orWhere('ip_address', 'like', "%{$searchValue}%");
                    });
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin-log.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any payroll !');
        }

        return response()->json(['message' => 'has been created!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('backend.pages.admin-log.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        if (is_null($this->user) || !$this->user->can('admin-log.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any payroll !');
        }

        // return view('backend.pages.admin-log.edit');
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
        if (is_null($this->user) || !$this->user->can('admin-log.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any payroll !');
        }

        session()->flash('success', 'has been updated !!');
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
        if (is_null($this->user) || !$this->user->can('admin-log.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any payroll !');
        }

        return response()->json( ['data' => ['message' => 'Record already deleted.' ] ], 200);
    }
}
