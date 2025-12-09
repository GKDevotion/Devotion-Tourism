<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
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

        if (!fetchSinglePermission( $this->user, 'admin.currency', 'view') ) {
            abort(403, 'Sorry !! You are Unauthorized to view Currency !');
        }

        $currency = Currency::all();
        $auth = $this->user;
        return view('backend.pages.currency.index', compact('currency', 'auth'));
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

        if (!fetchSinglePermission( $this->user, 'admin.currency', 'add') ) {
            abort(403, 'Sorry !! You are Unauthorized to create Role !');
        }

        $auth = $this->user;
        return view('backend.pages.currency.create', compact('auth'));
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

        if (!fetchSinglePermission( $this->user, 'admin.currency', 'add') ) {
            abort(403, 'Sorry !! You are Unauthorized to create Currency !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a currency name',
        ]);

        $dataObj = new Currency();
        $dataObj->name = $request->name;
        $dataObj->slug = convertStringToSlug( $request->name );
        $dataObj->code = $request->code;
        $dataObj->save();

        session()->flash('success', $request->name.' currency has been created !!');
        return redirect()->route('admin.currency.index');
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

        if (!fetchSinglePermission( $this->user, 'admin.currency', 'edit') ) {
            abort(403, 'Sorry !! You are Unauthorized to edit Role !');
        }

        $currency = Currency::find($id);
        $auth = $this->user;
        return view('backend.pages.currency.edit', compact('currency', 'auth'));
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

        if (!fetchSinglePermission( $this->user, 'admin.currency', 'edit') ) {
            abort(403, 'Sorry !! You are Unauthorized to edit Role !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $dataObj = Currency::find( $id );
        $dataObj->name = $request->name;
        $dataObj->slug = convertStringToSlug( $request->name );
        $dataObj->code = $request->code;
        $dataObj->save();

        session()->flash('success', $request->name.' role has been updated !!');
        return redirect()->route('admin.currency.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.currency', 'delete') ) {
            abort(403, 'Sorry !! You are Unauthorized to delete Role !');
        }

        $dataObj = Currency::find( $id );
        if ( !is_null( $dataObj ) ) {
            $dataObj->delete();
        }

        return response()->json( ['data' => ['message' => 'Record has been successfully deleted.' ] ], 200);
    }
}
