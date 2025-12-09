<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
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
        if (is_null($this->user) || !$this->user->can('services.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any services !');
        }

        $dataArr = Services::select('id', 'name', 'parent_id', 'status', 'updated_at')->orderBy('parent_id')->get();
        return view('backend.pages.services.index', compact('dataArr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('services.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any services !');
        }

        // $menuArr  = Services::select( 'id', 'name' )->get();
        return view('backend.pages.services.create'); //, compact('menuArr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('services.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any services !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'sort_order' => 'required',
        ]);

        $dataObj = new Services();
        $dataObj->parent_id = $request->parent_id;
        $dataObj->name = $request->name;
        $dataObj->slug = convertStringToSlug($request->name);
        $dataObj->status = $request->status;
        $dataObj->sort_order = $request->sort_order;
        $dataObj->save();

        session()->flash('success', $dataObj->name . ' has been created !!');
        return redirect()->route('admin.services.index');
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
        if (is_null($this->user) || !$this->user->can('services.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any services !');
        }

        $menuArr = Services::select('id', 'name')->get();
        $data = Services::find($id);
        return view('backend.pages.services.edit', compact('data', 'menuArr'));
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
        if (is_null($this->user) || !$this->user->can('services.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any services !');
        }

        $request->validate([
            'name' => 'required',
            'sort_order' => 'required',
        ]);

        $dataObj = Services::find($id);
        $dataObj->parent_id = $request->parent_id;
        $dataObj->name = $request->name;
        $dataObj->slug = convertStringToSlug($request->name);
        $dataObj->status = $request->status;
        $dataObj->sort_order = $request->sort_order;
        $dataObj->save();

        session()->flash('success', $dataObj->name . ' menu has been updated !!');
        return redirect()->route('admin.services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('services.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any services !');
        }

        $dataObj = Services::find($id);
        if (!is_null($dataObj)) {
            $dataObj->delete();
        }

        return response()->json(['data' => ['message' => "'" . $dataObj->name . '" has been successfully deleted.']], 200);
    }
}
