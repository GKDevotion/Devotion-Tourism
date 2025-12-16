<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories;
use App\Models\Admin\Package;
use App\Models\Admin\Visa;
use App\Models\PackageImageMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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

        
        // $posterName = null;

        // // ✅ Handle upload
        // if ($request->hasFile('poster')) {
        //     $poster = $request->file('poster');

        //     if ($poster->isValid()) { // extra check
        //         $posterName = time() . '_' . preg_replace('/\s+/', '_', $poster->getClientOriginalName());
        //         $poster->storeAs('public/poster', $posterName);
        //     } else {
        //         return back()->withErrors(['poster' => 'The file failed to upload properly.']);
        //     }
        // }


        $package = new Package();
        $manager = new ImageManager(new Driver());
// ------------------------------
// POSTER UPLOAD (PDF ONLY)
// ------------------------------
$posterName = null;

if ($request->hasFile('poster')) {
    $poster = $request->file('poster');

    // Validate file type (PDF)
    if ($poster->isValid() && $poster->getClientOriginalExtension() === 'pdf') {

        // Ensure the directory exists
        if (!Storage::disk('public')->exists('poster')) {
            Storage::disk('public')->makeDirectory('poster');
        }

        // Clean file name and prepend timestamp
        $posterName = time() . '_' . preg_replace('/\s+/', '_', $poster->getClientOriginalName());

        // Store the file in 'storage/app/public/poster'
        $poster->storeAs('poster', $posterName, 'public');

    } else {
        return back()->withErrors(['poster' => 'Please upload a valid PDF file.']);
    }
}

        // ------------------------------
        // IMAGE UPLOAD (BANNER)
        // ------------------------------
        if ($request->hasFile('image')) {
            Storage::makeDirectory('public/package/banner');

            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $bannerPath = storage_path('app/public/package/banner/' . $filename);

            $manager->read($image)->resize(1519, 417)->save($bannerPath);
            $package->image = 'package/banner/' . $filename;
        }

        // ------------------------------
        // FILL OTHER FIELDS
        // ------------------------------
        $package->user_id = auth()->guard('admin')->user()->id;
        $package->website_id = $this->websiteDetails['id'];
        $package->category_id = $request->category_id;
        $package->sub_category_id = $request->sub_category_id;
        $package->title = $request->title;
        $package->slug = rtrim(convertStringToSlug($request->title), "-");
        $package->short_description = $request->short_description;
        $package->description = $request->description;
        $package->keyword = $request->keyword;
        $package->status = $request->status;
        $package->location = $request->location;
        $package->poster = $posterName;
        $package->adult_size = $request->adult_size;
        $package->tour_type = $request->tour_type;
        $package->duration = $request->duration;
        $package->price = $request->price;
        $package->adult_price = $request->adult_price;
        $package->child_price = $request->child_price;
        $package->group_price = $request->group_price;
        $package->start_date = $request->start_date;
        $package->end_date = $request->end_date;
        $package->discount = $request->discount;
        $package->term_condition = $request->term_condition;

        if ($request->has('inclusive')) $package->inclusive = json_encode($request->inclusive);
        if ($request->has('exclusive')) $package->exclusive = json_encode($request->exclusive);
        if ($request->has('itenery')) $package->itenery = implode(',', $request->itenery);
        if ($request->has('faq')) $package->faq = implode(',', $request->faq);

        $package->save();


        // Step 2: Generate tour_id
        $package->tour_id = 'TID' . str_pad($package->id, 4, '0', STR_PAD_LEFT);
        $package->short_url = _en($package->id);
        $package->save(); // save the tour_id and short_url

        // Step 3: Upload card images to package_image_map
        Storage::makeDirectory('public/package/card');

        for ($i = 0; $i <= 3; $i++) {
            $inputName = 'lot_file_' . $i;
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = storage_path('app/public/package/card/' . $filename);
                $manager->read($file)->resize(1519, 417)->save($path);

                PackageImageMap::create([
                    'tour_id' => $package->tour_id, // use updated package tour_id
                    'image' => 'package/card/' . $filename,
                    'filename' => $filename,
                    'status' => 1
                ]);
            }
        }

        $request->session()->flash('message', 'Package successfully created');
        return redirect()->route('admin.package.index');
    }



    /**
     * Generate a unique tour_id
     */
    private function generateUniqueTourId()
    {
        // Use DB to get the max numeric part of tour_id
        $lastNumber = Package::selectRaw("MAX(CAST(SUBSTRING(tour_id, 4) AS UNSIGNED)) as max_id")->value('max_id');

        $newNumber = $lastNumber ? $lastNumber + 1 : 1;

        return 'TID' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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
        // fetch card images using tour_id
        $cardImages = PackageImageMap::where('tour_id', $dataArr->tour_id)
            ->orderBy('id', 'ASC')
            ->get();
        $packageArr = Package::select('id', 'title')->where([
            'status' => 1,
            'website_id' => $headerInfo['id'],
        ])
            ->get();

        return view('backend.pages.packages.edit', compact('dataArr', 'categories','cardImages', 'headerInfo', 'packageArr'));
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


        $manager = new ImageManager(new Driver());

        /* -----------------------------
            IMAGE UPDATE (BANNER + CARD)
            ------------------------------*/
        if ($request->hasFile('image')) {

            // 🔥 DELETE OLD IMAGES
            if ($package->image && Storage::exists('public/' . $package->image)) {
                Storage::delete('public/' . $package->image);
            }

            // if ($package->card_image && Storage::exists('public/' . $package->card_image)) {
            //     Storage::delete('public/' . $package->card_image);
            // }

            // Ensure directories exist
            Storage::makeDirectory('public/package/banner');
            // Storage::makeDirectory('public/package/card');

            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Banner Image
            $bannerPath = storage_path('app/public/package/banner/' . $filename);
            $manager->read($image)
                ->resize(1519, 417)
                ->save($bannerPath);

            // // Card Image
            // $cardPath = storage_path('app/public/package/card/' . $filename);
            // $manager->read($image)
            //     ->resize(364, 243)
            //     ->save($cardPath);

            $package->image = 'package/banner/' . $filename;
            // $package->card_image = 'package/card/' . $filename;
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
        $package->adult_price = $request->adult_price;
        $package->child_price = $request->child_price;
        $package->group_price = $request->group_price;
        $package->discount = $request->discount;
        $package->term_condition = $request->term_condition;
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

        return response()->json(['data' => ['message' => 'Package successfully deleted.']], 200);
        // return redirect()->route('admin.role');
    }
}
