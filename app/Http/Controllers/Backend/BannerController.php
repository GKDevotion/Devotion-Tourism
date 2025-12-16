<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
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

        if (is_null($this->user)) {
            return redirect('admin/login');
        }

        if (!fetchSinglePermission($this->user, 'admin.banner', 'view')) {
            abort(403, 'Sorry !! You are Unauthorized to view Banner !');
        }

        $banners = Banner::select(
            'id',
            'image',
            'name',
            'sub_title',
            'status',
            'updated_at'
        )->orderBy('id', 'desc')->get();

        // $dataArr = City::limit(1000)->get();
        return view('backend.pages.banner.index', compact('banners'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user)) {
            return redirect('admin/login');
        }

        if (!fetchSinglePermission($this->user, 'admin.banner', 'add')) {
            abort(403, 'Sorry !! You are Unauthorized to create Banner !');
        }

        return view('backend.pages.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user)) {
            return redirect('admin/login');
        }

        if (!fetchSinglePermission($this->user, 'admin.banner', 'add')) {
            abort(403, 'Sorry !! You are Unauthorized to create Banner !');
        }
        // ✅ Validate input
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);


        // ✅ Save to database
        $dataObj = new Banner();
        $dataObj->name = $request->name;
        $dataObj->sub_title = $request->sub_title;
        $dataObj->status = $request->status;

        // Handle Image Upload
        if ($request->hasFile('image')) {

            // Create folder if not exists
            if (!file_exists(storage_path('app/banner'))) {
                mkdir(storage_path('app/banner'), 0777, true);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Save in storage/app/banner
            $file->storeAs('banner', $fileName);

            // Save filename in DB
            $dataObj->image = $fileName;
        }


        $dataObj->save();

        session()->flash('success', $dataObj->name . ' record has been created successfully!');
        return redirect()->route('admin.banner.index');
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

        if (!fetchSinglePermission( $this->user, 'admin.banner', 'edit') ) {
            abort(403, 'Sorry !! You are Unauthorized to Banner Role !');
        }
        $data = Banner::find($id);

        return view('backend.pages.banner.edit', compact('data'));
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
        
        if (!fetchSinglePermission( $this->user, 'admin.banner', 'edit') ) {
            abort(403, 'Sorry !! You are Unauthorized to Banner Role !');
        }

        // Validate input
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        // Fetch existing record
        $dataObj = Banner::findOrFail($id);

        $dataObj->name = $request->name;
        $dataObj->sub_title = $request->sub_title;
        $dataObj->status = $request->status;

        // Handle image update
        if ($request->hasFile('image')) {

            // Ensure folder exists
            if (!file_exists(storage_path('app/banner'))) {
                mkdir(storage_path('app/banner'), 0777, true);
            }

            // Delete old image if exists
            if (!empty($dataObj->image) && file_exists(storage_path('app/banner/' . $dataObj->image))) {
                unlink(storage_path('app/banner/' . $dataObj->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Save new file
            $file->storeAs('banner', $fileName);

            // Update DB with new file
            $dataObj->image = $fileName;
        }

        $dataObj->save();

        session()->flash('success', $dataObj->name . ' record has been updated successfully!');
        return redirect()->route('admin.banner.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
          if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.banner', 'delete') ) {
            abort(403, 'Sorry !! You are Unauthorized to delete Banner !');
        }

        $dataObj = Banner::find($id);
        if ($dataObj) {
            $dataObj->delete();
            return response()->json(['data' => ['message' => $dataObj->name . ' record has been successfully deleted.']], 200);
        } else {
            return response()->json(['data' => ['message' => 'Record already deleted.']], 200);
        }
    }
}
