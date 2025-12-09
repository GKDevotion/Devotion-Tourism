<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
class MenuController extends Controller
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

        if( !fetchSinglePermission( $this->user, 'admin.menu', 'view') ){
            $logArr['description'] = "Unauthorized try to view any menu";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view this !');
        }

        $dataArr = AdminMenu::select('id', 'parent_id', 'name', 'slug', 'group_name', 'class_name', 'sort_order', 'status', 'updated_at')->get();
        $user = $this->user;

        $logArr['description'] = "Load menu data successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.menu.index', compact('dataArr', 'user'));
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
        $logArr['action'] = "C";

        if (!fetchSinglePermission( $this->user, 'admin.menu', 'add') ) {
            $logArr['description'] = "Unauthorized try to create menu";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create any menu !');
        }

        $logArr['description'] = "Load Menu form successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.menu.create');//, compact('menuArr'));
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

        if ( !fetchSinglePermission( $this->user, 'admin.menu', 'add') ) {
            $logArr['description'] = "Unauthorized try to store menu data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create any menu !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'class_name' => 'required',
            'parent_id' => 'required',
            'status' => 'required',
        ]);

        $adminMenu = new AdminMenu();
        $adminMenu->class_name = $request->class_name;
        $adminMenu->parent_id = $request->parent_id;
        $adminMenu->name = $request->name;
        $adminMenu->slug = convertStringToSlug( $request->name );
        $adminMenu->group_name = $request->group_name;
        $adminMenu->icon = $request->icon;
        $adminMenu->status = $request->status;
        $adminMenu->sort_order = $request->sort_order;
        $adminMenu->save();

        //clear all cookie cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        $logArr['description'] = $adminMenu->name.' menu has been created !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.menu.index');
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

        if ( !fetchSinglePermission( $this->user, 'admin.menu', 'edit') ) {
            $logArr['description'] = "Unauthorized try to edit menu data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit menu data!');
        }

        $menuArr  = AdminMenu::select( 'id', 'name' )->get();
        $data = AdminMenu::find($id);
        $auth = $this->user;

        $logArr['description'] = "Menu form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.menu.edit', compact('data', 'menuArr', 'auth'));
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

        if ( !fetchSinglePermission( $this->user, 'admin.menu', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update menu data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to update menu data!');
        }

        $request->validate([
            'name' => 'required',
            'class_name' => 'required',
            'parent_id' => 'required',
            'status' => 'required',
        ]);

        $adminMenu = AdminMenu::find( $id );
        $adminMenu->class_name = $request->class_name;
        $adminMenu->parent_id = $request->parent_id;
        $adminMenu->name = $request->name;
        $adminMenu->slug = convertStringToSlug( $request->name );
        $adminMenu->group_name = $request->group_name;
        $adminMenu->icon = $request->icon;
        $adminMenu->status = $request->status;
        $adminMenu->sort_order = $request->sort_order;
        $adminMenu->save();

        //clear all cookie cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        $logArr['description'] = $adminMenu->name.' menu has been updated !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.menu.index');
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

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.menu', 'delete') ) {
            $logArr['description'] = "Unauthorized try to delete menu data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete menu data!');
        }

        $adminMenu = AdminMenu::find($id);
        if (!is_null($adminMenu)) {
            $adminMenu->delete();
        }

        $logArr['description'] = "'".$adminMenu->name.'" menu has been successfully deleted.';
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => $logArr['description'] ] ], 200);
    }
}
