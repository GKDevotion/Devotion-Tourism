<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RolesController extends Controller
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
    public function index()
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!fetchSinglePermission( $this->user, 'admin.role', 'view') ) {
            abort(403, 'Sorry !! You are Unauthorized to view Role !');
        }

        $roles = Role::all();
        $auth = $this->user;
        return view('backend.pages.roles.index', compact('roles', 'auth'));
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

        if ( !fetchSinglePermission( $this->user, 'admin.role', 'add') ) {
            abort(403, 'Sorry !! You are Unauthorized to create Role !');
        }

        $auth = $this->user;
        return view('backend.pages.roles.create', compact('auth'));
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

        if (!fetchSinglePermission( $this->user, 'admin.role', 'add') ) {
            abort(403, 'Sorry !! You are Unauthorized to create Role !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name',
        ]);

        $slug = convertStringToSlug( $request->name );
        Role::create( ['name' => $request->name, 'slug' => $slug, 'guard_name' => $request->guard_name] );

        session()->flash('success', $request->name.' role has been created !!');
        return redirect()->route('admin.role.index');
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

        if (!fetchSinglePermission( $this->user, 'admin.role', 'edit') ) {
            abort(403, 'Sorry !! You are Unauthorized to edit Role !');
        }

        $role = Role::find($id);
        $auth = $this->user;
        return view('backend.pages.roles.edit', compact('role', 'auth'));
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

        if (!$this->user->can('role.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit Role !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $role = Role::find( $id );
        $role->name = $request->name;
        $role->slug = convertStringToSlug( $request->name );
        $role->save();

        session()->flash('success', $request->name.' role has been updated !!');
        return redirect()->route('admin.role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.role', 'delete') ) {
            abort(403, 'Sorry !! You are Unauthorized to delete Role !');
        }

        $dataObj = Role::find( $id );
        if ( !is_null( $dataObj ) ) {
            $dataObj->delete();
        }

        return response()->json( ['data' => ['message' => 'Record has been successfully deleted.' ] ], 200);
    }
}
