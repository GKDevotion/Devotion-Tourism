<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories;
use App\Models\Admin\CompanyCategories;
use App\Models\Admin\FaqCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class CategoriesController extends Controller
{
    public $websiteDetails = "";

    /**
     * @Function:        <__construct>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Create a new controller instance.>
     * @return void
     */
    function __construct()
    {
        $this->websiteDetails = getHeaderInformation();
    }

    /**
     * @Function:        <index>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headerInfo = $this->websiteDetails;
        $dataArr = Categories::where( [
            // 'parent_id' => 0, 
            'website_id' => $headerInfo['id'] 
        ] )
        ->get();

        // $parentArr = Categories::where( [
        //     'parent_id' => 0, 
        //     'website_id' => $headerInfo['id'] 
        // ] )
        // ->pluck('title', 'id');
        

        return view('backend.pages.category.index', compact( 'dataArr', 'headerInfo') );
    }

    /**
     * @Function:        <create>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $headerInfo = $this->websiteDetails;
        $parentArr = Categories::where( [
            // 'parent_id' => 0, 
            'website_id' => $headerInfo['id'] 
        ] )
        ->pluck('title', 'id');

        return view('backend.pages.category.create', compact( 'parentArr', 'headerInfo' ) );
    }

    /**
     * @Function:        <store>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|min:1|max:64',
    ]);

    $slug = convertStringToSlug($request->title);

    if ($request->parent_id && $request->parent_id != 0) {

        $parent = Categories::where([
            'website_id' => $this->websiteDetails['id'],
            'id' => $request->parent_id
        ])->first();

        if ($parent) {
            $slug = convertStringToSlug($parent->title) . '-' . $slug;
        }
    }

    $category = new Categories();
    $manager = new ImageManager(new Driver());

        /* -----------------------------
        IMAGE UPLOAD (BANNER + CARD)
        ------------------------------*/
        if ($request->hasFile('image')) {

            Storage::makeDirectory('public/category');
  

            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Banner (1519x417)
            $Path = storage_path('app/public/category/' . $filename);

            $manager->read($image)
                ->resize(1519, 417)
                ->save($Path);

    
            $category->image = 'category/' . $filename;

        }
    $category->title = $request->title;
    $category->slug = $slug;
    $category->parent_id = $request->parent_id ?? 0;
    $category->website_id = $this->websiteDetails['id'];
    $category->status = $request->status;

    $category->save();

    $request->session()->flash('message', 'Category successfully created');

    return redirect()->route('admin.category.index');
}


    /**
     * @Function:        <show>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Categories::with('user')->with('status')->find($id);
        return view('admin.category.show', [ 'note' => $note ]);
    }

    /**
     * @Function:        <edit>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id ){

        $dataArr = Categories::find($id);
        $headerInfo = $this->websiteDetails;
        $parentArr = Categories::where( [
            // 'parent_id' => 0, 
            'website_id' => $headerInfo['id'] 
        ] )
        ->where( 'id', '!=', $id )
        ->pluck('title', 'id');
        return view('backend.pages.category.edit', compact( 'dataArr', 'parentArr', 'headerInfo' ) );
    }

    /**
     * @Function:        <update>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:1|max:64'
        ]);

        $slug = convertStringToSlug( $request->title );
        if( $request->parent_id ){
            $parentArr = Categories::where( [
                'website_id' => $this->websiteDetails['id'],
                'id' => $request->parent_id
            ] )
            ->select( 'title' )
            ->first();

            $slug = convertStringToSlug( $parentArr->title ).'-'.$slug;
        }
        
        $category = Categories::find($id);
         $manager = new ImageManager(new Driver());

        /* -----------------------------
            IMAGE UPDATE (BANNER + CARD)
            ------------------------------*/
        if ($request->hasFile('image')) {

            // 🔥 DELETE OLD IMAGES
            if ($category->image && Storage::exists('public/' . $category->image)) {
                Storage::delete('public/' . $category->image);
            }

            // if ($package->card_image && Storage::exists('public/' . $package->card_image)) {
            //     Storage::delete('public/' . $package->card_image);
            // }

            // Ensure directories exist
            Storage::makeDirectory('public/category');
            // Storage::makeDirectory('public/package/card');

            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Banner Image
            $Path = storage_path('app/public/category/' . $filename);
            $manager->read($image)
                ->resize(1519, 417)
                ->save($Path);

            // // Card Image
            // $cardPath = storage_path('app/public/package/card/' . $filename);
            // $manager->read($image)
            //     ->resize(364, 243)
            //     ->save($cardPath);

            $category->image = 'category/' . $filename;
            // $package->card_image = 'package/card/' . $filename;
        }

        $category->title = $request->title;
        $category->slug = $slug;
        $category->parent_id = $request->parent_id;
        $category->website_id = $this->websiteDetails['id'];
        $category->status = $request->status;
        $category->save();
        $request->session()->flash('message', 'Category successfully updated');
        return redirect()->route('admin.category.index');
    }

    /**
     * @Function:        <destroy>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <23-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Categories::find($id);
        if ($del) {
            $del->delete();
        }
        return response()->json( ['data' => ['message' => 'Category successfully deleted.' ] ], 200);
    }

    
  }
