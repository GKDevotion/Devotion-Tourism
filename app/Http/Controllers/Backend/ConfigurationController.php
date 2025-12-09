<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
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

        if (!fetchSinglePermission( $this->user, 'configurations', 'view') ) {
            $logArr['description'] = "Unauthorized try to view any configuration";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view any configuration !');
        }

        $dataArr = Configuration::get();

        $logArr['description'] = "Loaded configuration successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.configurations.index', compact('dataArr'));
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

        if (!fetchSinglePermission( $this->user, 'configurations', 'add') ) {
            $logArr['description'] = "Unauthorized try to create configuration";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create configuration!');
        }

        $logArr['description'] = "Configuration form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.configurations.create');
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

        if (!fetchSinglePermission( $this->user, 'configuration', 'add') ) {
            $logArr['description'] = "Unauthorized try to store configuration data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to store configuration data!');
        }

        // Validation Data
        $request->validate([
            'display_name' => 'required',
            'key' => 'required',
            'value' => 'required',
        ]);

        $dataObj = new Configuration();
        $dataObj->display_name = $request->display_name;
        $dataObj->key = $request->key;
        $dataObj->value = $request->value;
        $dataObj->save();

        $logArr['description'] = $dataObj->key.' configuration has been created !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.configurations.index');
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
        $logArr['action'] = "S";

        if (!fetchSinglePermission( $this->user, 'account-field', 'edit') ) {
            $logArr['description'] = "Unauthorized try to create company account indexing";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Company Account Indexing !');
        }

        $data = Configuration::find($id);
        return view('backend.pages.configurations.edit', compact('data'));
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

        if (!fetchSinglePermission( $this->user, 'configurations', 'add') ) {
            $logArr['description'] = "Unauthorized try to update configuration details";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to update configuration details!');
        }

        $request->validate([
            'display_name' => 'required',
            'key' => 'required',
            'value' => 'required',
        ]);

        $dataObj = Configuration::find( $id );
        $dataObj->display_name = $request->display_name;
        $dataObj->key = $request->key;
        $dataObj->value = $request->value;
        $dataObj->save();

        $logArr['description'] = $dataObj->key.' configuration has been updated !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.configurations.index');
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

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'configurations', 'add') ) {
            $logArr['description'] = "Unauthorized try to delete any configuration";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete any configuration !');
        }

        $dataObj = Configuration::find($id);
        if (!is_null($dataObj)) {
            $dataObj->delete();
        }

        $logArr['description'] = "'".$dataObj->key.'" configuration has been successfully deleted.';
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => $logArr['description'] ] ], 200);
    }
}
