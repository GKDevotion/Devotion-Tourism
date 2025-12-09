<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContinentsController extends Controller
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

        if (!$this->user->can('continent.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any continent !');
        }

        $dataArr = Continent::select('id', 'name', 'status', 'updated_at')->get();
        return view('backend.pages.continents.index', compact('dataArr'));
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

        if (!$this->user->can('continent.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any continent !');
        }

        return view('backend.pages.continents.create' );
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

        if (!$this->user->can('continent.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any continent !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // Create New Server Record
        $dataObj = new Continent();
        $dataObj->name = $request->name;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', 'Record has been created !!');
        return redirect()->route('admin.continent.index');
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

        if (!$this->user->can('continent.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any continent !');
        }

        $data = Continent::find($id);
        return view('backend.pages.continents.edit', compact('data'));
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

        if (!$this->user->can('continent.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any continent !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // Create New Server Record
        $dataObj = Continent::find( $id );
        $dataObj->name = $request->name;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', 'Records has been updated !!');
        return redirect()->route('admin.continent.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('continent.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any continent !');
        }

        $dataObj = Continent::find($id);
        if ( $dataObj ) {
            $dataObj->delete();
            return response()->json( ['data' => ['message' => $dataObj->name.' record has been successfully deleted.'] ], 200 );
        } else {
            return response()->json( ['data' => ['message' => 'Record already deleted.'] ], 200);
        }
    }
}
