<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories;
use App\Models\Admin\Package;
use App\Models\Admin\Visa;
use Illuminate\Http\Request;
use Image;

class PackageController extends Controller
{
    public $websiteDetails = "";
    /**
     * @Function:        <__construct>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Create a new controller instance.>
     * @return void
     */
    public function __construct()
    {
        $this->websiteDetails = getHeaderInformation();
        // $this->middleware('admin');
    }

    /**
     * @Function:        <index>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headerInfo = $this->websiteDetails;
        $dataArr = Package::orderBy('id', 'desc')
            ->where([
                'website_id' => $headerInfo['id']
            ])

            ->get();

        // dd($dataArr);
        return view('backend.pages.packages.index', compact('dataArr', 'headerInfo'));
    }

    /**
     * @Function:        <create>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $headerInfo = $this->websiteDetails;

        $categories = Categories::with('childrenRecursive')->where(['status' => 1, 'parent_id' => 0])->get();

        $packageArr = Package::select('id', 'title')->where([
            'status' => 1,
            'website_id' => $headerInfo['id'],
        ])
            ->get();

        return view('backend.pages.packages.create', compact('categories', 'packageArr', 'headerInfo'));
    }

    /**
     * @Function:        <store>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
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
            'title' => 'required|min:1|max:256',
            'short_description' => 'required',
            'description' => 'required',
            'keyword' => 'required',
            'duration' => 'required',
            'tour_type' => 'required',
            'adult_size' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $package = new Package();

        if ($request->hasFile('image')) {
            $filename = $request->image->getClientOriginalName();

            $originalImage = $request->file('image');
            $destinationImagePath = storage_path('/app/public/package/banner');
            $img = Image::make($originalImage->path());
            $img->resize(1519, 417, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->save($destinationImagePath . '/' . $filename);
            $package->image = "public/package/banner/" . $filename;

            $destinationCardImagePath = storage_path('/app/public/package/card');
            $img = Image::make($originalImage->path());
            $img->resize(364, 243, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->save($destinationCardImagePath . '/' . $filename);
            $package->card_image = "public/package/card/" . $filename;
        }

        for ($i = 0; $i < 4; $i++) {
            if ($request->hasFile('lot_file_' . $i)) {
                $objFile = 'lot_file_' . $i;
                $filename = $request->$objFile->getClientOriginalName();

                $originalImage = $request->file('lot_file_' . $i);
                $destinationImagePath = storage_path('/app/public/package/card');
                $img = Image::make($originalImage->path());
                $img->resize(1519, 417, function ($constraint) {
                    $constraint->aspectRatio();
                })
                    ->save($destinationImagePath . '/' . $filename);

                $detail_image = 'detail_image_' . ($i + 1);
                $package->$detail_image = "public/package/card/" . $filename;
            }
        }

        $user_id = auth()->guard('admin')->user()->id;
        $package->user_id = $user_id;
        $package->website_id = $this->websiteDetails['id'];
        $package->category_id = $request->category_id;
        $package->sub_category_id = $request->sub_category_id;
        $package->title = $request->title;
        $package->slug = rtrim(convertStringToSlug($request->title), "-");
        $package->short_description = $request->short_description;
        $package->description = $request->description;
        // $package->bullet_points = json_encode( $request->bullet_points );
        $package->keyword = $request->keyword;
        $package->status = $request->status;
        $package->location = $request->location;
        $package->adult_size = $request->adult_size;
        $package->tour_type = $request->tour_type;
        $package->duration = $request->duration;
        $package->price = $request->price;
        $package->start_date = $request->start_date;
        $package->end_date = $request->end_date;
        $package->discount = $request->discount;
        $package->save();

        $package->short_url = _en($package->id);
        $package->save();

        $request->session()->flash('message', 'Package successfully created');
        return redirect()->route('admin.package.index');
    }

    /**
     * @Function:        <show>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Package::with('user')->with('status')->find($id);
        return view('backend.pages.packages.show', ['note' => $note]);
    }

    /**
     * @Function:        <edit>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $headerInfo = $this->websiteDetails;
        $dataArr = Package::find($id);
        $categories = Categories::with('childrenRecursive')->where(['status' => 1, 'parent_id' => 0])->get();

        $packageArr = Package::select('id', 'title')->where([
            'status' => 1,
            'website_id' => $headerInfo['id'],
        ])
            ->get();

        return view('backend.pages.packages.edit', compact('dataArr', 'categories', 'headerInfo', 'packageArr'));
    }

    /**
     * @Function:        <update>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
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
            'title' => 'required|min:1|max:256',
            'short_description' => 'required',
            'description' => 'required',
            'keyword' => 'required',
            'duration' => 'required',
            'tour_type' => 'required',
            'adult_size' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $package = Package::find($id);

        if ($request->hasFile('image')) {
            $filename = $request->image->getClientOriginalName();

            $originalImage = $request->file('image');
            $destinationImagePath = storage_path('/app/public/package/banner');
            $img = Image::make($originalImage->path());
            $img->resize(1519, 417, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->save($destinationImagePath . '/' . $filename);
            $package->image = "public/package/banner/" . $filename;

            $destinationCardImagePath = storage_path('/app/public/package/card');
            $img = Image::make($originalImage->path());
            $img->resize(364, 243, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->save($destinationCardImagePath . '/' . $filename);
            $package->card_image = "public/package/card/" . $filename;
        }

        // $package->user_id = auth()->guard('admin')->user()->id;
        $package->website_id = $this->websiteDetails['id'];
        $package->category_id = $request->category_id;
        $package->sub_category_id = $request->sub_category_id;
        $package->title = $request->title;
        $package->slug = rtrim(convertStringToSlug($request->title), "-");
        $package->short_description = $request->short_description;
        $package->description = $request->description;
        // $package->bullet_points = json_encode( $request->bullet_points );
        $package->keyword = $request->keyword;
        $package->status = $request->status;
        $package->location = $request->location;
        $package->adult_size = $request->adult_size;
        $package->tour_type = $request->tour_type;
        $package->duration = $request->duration;
        $package->price = $request->price;
        $package->discount = $request->discount;
        $package->start_date = $request->start_date;
        $package->end_date = $request->end_date;
        $package->save();

        $request->session()->flash('message', 'Package successfully updated');
        return redirect()->route('admin.package.index');
    }

    /**
     * @Function:        <destroy>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <25-11-2021>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Package::find($id);
        if ($del) {
            $del->delete();
        }

        return response()->json(['data' => ['message' => 'Blog successfully deleted.']], 200);
        // return redirect()->route('admin.role');
    }
}
