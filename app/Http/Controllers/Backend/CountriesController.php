<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CountriesController extends Controller
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

        if (!$this->user->can('country.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view Country !');
        }

        $dataArr = Cache::remember('dataArr', 10, function () {
            return Country::with('continent')->select('id', 'continent_id', 'name', 'iso2', 'iso3', 'numeric_code', 'capital', 'currency', 'currency_symbol', 'latitude', 'longitude', 'status', 'updated_at')->get();
        });

        return view('backend.pages.countries.index', compact('dataArr'));
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

        if (!$this->user->can('country.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create Country !');
        }

        $continentArr  = Continent::all();
        return view('backend.pages.countries.create', compact( 'continentArr' ) );
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

        if (!$this->user->can('country.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create Country !');
        }

        // Validation Data
        $request->validate([
            'continent_id' => 'required',
            'name' => 'required',
            'iso3' => 'required',
            'numeric_code' => 'required',
            'iso2' => 'required',
            'phone_code' => 'required',
            'capital' => 'required',
            'currency' => 'required',
            'currency_name' => 'required',
            'currency_symbol' => 'required',
            'tld' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Create New Server Record
        $dataObj = new Country();
        $dataObj->continent_id  = $request->continent_id ;
        $dataObj->name = $request->name;
        $dataObj->iso3 = $request->iso3;
        $dataObj->numeric_code = $request->numeric_code;
        $dataObj->iso2 = $request->iso2;
        $dataObj->phone_code  = $request->phone_code ;
        $dataObj->capital = $request->capital;
        $dataObj->currency = $request->currency;
        $dataObj->currency_name = $request->currency_name;
        $dataObj->currency_symbol = $request->currency_symbol;
        $dataObj->tld  = $request->tld ;
        $dataObj->latitude = $request->latitude;
        $dataObj->longitude = $request->longitude;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', 'Record has been created !!');
        return redirect()->route('admin.country.index');
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

        if (!$this->user->can('country.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit Country !');
        }

        $data = Country::find($id);
        $continentArr  = Continent::all();
        return view('backend.pages.countries.edit', compact('data', 'continentArr'));
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

        if (!$this->user->can('country.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit Country !');
        }

        // Validation Data
        $request->validate([
            'continent_id' => 'required',
            'name' => 'required',
            'iso3' => 'required',
            'numeric_code' => 'required',
            'iso2' => 'required',
            'phone_code' => 'required',
            'capital' => 'required',
            'currency' => 'required',
            'currency_name' => 'required',
            'currency_symbol' => 'required',
            'tld' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Create New Server Record
        $dataObj = Country::find( $id );
        $dataObj->continent_id  = $request->continent_id ;
        $dataObj->name = $request->name;
        $dataObj->iso3 = $request->iso3;
        $dataObj->numeric_code = $request->numeric_code;
        $dataObj->iso2 = $request->iso2;
        $dataObj->phone_code  = $request->phone_code ;
        $dataObj->capital = $request->capital;
        $dataObj->currency = $request->currency;
        $dataObj->currency_name = $request->currency_name;
        $dataObj->currency_symbol = $request->currency_symbol;
        $dataObj->tld  = $request->tld ;
        $dataObj->latitude = $request->latitude;
        $dataObj->longitude = $request->longitude;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', 'Records has been updated !!');
        return redirect()->route('admin.country.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('country.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete Country !');
        }

        $record = Country::find($id);
        if (!is_null($record)) {
            $record->delete();
        }

        // session()->flash('success', $record->name.' record has been deleted !!');
        return response()->json( ['data' => ['message' => "'".$record->name.'" has been successfully deleted.' ] ], 200);
    }
}
