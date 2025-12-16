<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class WebsitesController extends Controller
{
    /**
     * @Function:        <__construct>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Create a new controller instance.>
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    /**
     * @Function:        <index>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataArr = Website::get();
        return view('backend.pages.websites.index', compact('dataArr'));
    }

    /**
     * @Function:        <create>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.websites.create');
    }

    /**
     * @Function:        <store>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|min:1|max:30',
            'favicon'     => 'required|image|mimes:png,jpg,jpeg,ico,webp|max:1024',
            'header_logo' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'footer_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $website = new Website();
        $manager = new ImageManager(new Driver());

        /* -----------------------------
        FOLDERS
        ------------------------------*/
        Storage::disk('public')->makeDirectory('website/favicon');
        Storage::disk('public')->makeDirectory('website/logo');

        /* -----------------------------
        FAVICON (64x64)
        ------------------------------*/
        if ($request->hasFile('favicon')) {

            $faviconFile = $request->file('favicon');
            $faviconName = Str::uuid() . '.' . $faviconFile->getClientOriginalExtension();
            $faviconPath = storage_path('app/public/website/favicon/' . $faviconName);

            $manager->read($faviconFile)
                ->resize(64, 64)
                ->save($faviconPath);

            $website->favicon = 'website/favicon/' . $faviconName;
        }

        /* -----------------------------
        HEADER LOGO (300x100)
        ------------------------------*/
        if ($request->hasFile('header_logo')) {

            $headerFile = $request->file('header_logo');
            $headerName = Str::uuid() . '.' . $headerFile->getClientOriginalExtension();
            $headerPath = storage_path('app/public/website/logo/' . $headerName);

            $manager->read($headerFile)
                ->resize(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($headerPath);

            $website->header_logo = 'website/logo/' . $headerName;
        }

        /* -----------------------------
        FOOTER LOGO (300x100)
        ------------------------------*/
        if ($request->hasFile('footer_logo')) {

            $footerFile = $request->file('footer_logo');
            $footerName = Str::uuid() . '.' . $footerFile->getClientOriginalExtension();
            $footerPath = storage_path('app/public/website/logo/' . $footerName);

            $manager->read($footerFile)
                ->resize(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($footerPath);

            $website->footer_logo = 'website/logo/' . $footerName;
        }

        /* -----------------------------
        SAVE DATA
        ------------------------------*/
        $website->name = $request->name;
        $website->slug = rtrim(convertStringToSlug($request->name), '-');
        $website->is_run_advertisement = $request->is_run_advertisement ?? 0;
        $website->google_analytics_code = $request->google_analytics_code;
        $website->google_client_ca_pub_code = $request->google_client_ca_pub_code;
        $website->google_tag_manager_code = $request->google_tag_manager_code;
        $website->status = $request->status ?? 0;

        $website->save();

        /* -----------------------------
        ONLY ONE ACTIVE WEBSITE
        ------------------------------*/
        if ($website->status == 1) {
            Website::where('id', '!=', $website->id)->update(['status' => 0]);
        }

        return redirect()
            ->route('admin.website.index')
            ->with('message', 'Website successfully created');
    }
    /**
     * @Function:        <show>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $note = Website::find($id);
    //     return view('backend.pages.websites.show', ['note' => $note]);
    // }

    /**
     * @Function:        <edit>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataArr = Website::find($id);
        return view('backend.pages.websites.edit', compact('dataArr'));
    }

    /**
     * @Function:        <update>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
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
            'name' => 'required|min:1|max:30',
        ]);

        $website = Website::find($id);

    $manager = new ImageManager(new Driver());

    /* -----------------------------
       CREATE DIRECTORIES
    ------------------------------*/
    Storage::disk('public')->makeDirectory('website/favicon');
    Storage::disk('public')->makeDirectory('website/logo');

    /* -----------------------------
       UPDATE FAVICON
    ------------------------------*/
    if ($request->hasFile('favicon')) {

        // delete old
        if ($website->favicon && Storage::disk('public')->exists($website->favicon)) {
            Storage::disk('public')->delete($website->favicon);
        }

        $file = $request->file('favicon');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/public/website/favicon/' . $name);

        $manager->read($file)
            ->resize(64, 64)
            ->save($path);

        $website->favicon = 'website/favicon/' . $name;
    }

    /* -----------------------------
       UPDATE HEADER LOGO
    ------------------------------*/
    if ($request->hasFile('header_logo')) {

        if ($website->header_logo && Storage::disk('public')->exists($website->header_logo)) {
            Storage::disk('public')->delete($website->header_logo);
        }

        $file = $request->file('header_logo');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/public/website/logo/' . $name);

        $manager->read($file)
            ->resize(300, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($path);

        $website->header_logo = 'website/logo/' . $name;
    }

    /* -----------------------------
       UPDATE FOOTER LOGO
    ------------------------------*/
    if ($request->hasFile('footer_logo')) {

        if ($website->footer_logo && Storage::disk('public')->exists($website->footer_logo)) {
            Storage::disk('public')->delete($website->footer_logo);
        }

        $file = $request->file('footer_logo');
        $name = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/public/website/logo/' . $name);

        $manager->read($file)
            ->resize(300, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($path);

        $website->footer_logo = 'website/logo/' . $name;
    }


        $slug = rtrim(convertStringToSlug($request->name), "-");
        $website->name = $request->name;
        $website->slug = $slug;
        // $website->is_run_advertisement = $request->is_run_advertisement;
        // $website->google_analytics_code = $request->google_analytics_code;
        // $website->google_client_ca_pub_code = $request->google_client_ca_pub_code;
        // $website->google_tag_manager_code = $request->google_tag_manager_code;
        // $website->status = $request->status;
        $website->save();

        if ($request->status == 1) {
            Website::where('id', '!=', $website->id)->update(['status' => 0]);
        }

        $request->session()->flash('message', 'Website successfully updated');
        return redirect()->route('admin.website.index');
    }

    /**
     * @Function:        <destroy>
     * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
     * @Created On:      <30-09-2023>
     * @Last Modified By:Gautam Kakadiya
     * @Last Modified:   Gautam Kakadiya
     * @Description:     <This function work for Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Website::find($id);
        if ($del) {
            $del->delete();
        }

        return response()->json(['data' => ['message' => 'Website successfully deleted.']], 200);
    }
}
