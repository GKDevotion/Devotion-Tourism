<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Continent;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StatesController extends Controller
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
        if (is_null($this->user) || !$this->user->can('state.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view State !');
        }

        // $dataArr = State::limit(1000)->get();
        return view('backend.pages.states.index' );
    }

    /**
     *
     */
    public function ajaxIndex(){

        $query = State::query();

        $query->select('id', 'name', 'continent_id', 'country_id', 'iso2', 'latitude', 'longitude', 'status', 'updated_at');

        return DataTables::eloquent($query)
            ->addColumn('id', function(State $state) {
                return $state->id;
            })
            ->addColumn('name', function(State $state) {
                return $state->name;
            })
            ->addColumn('continent_name', function(State $state) {
                return $state->continent->name; // Display the continent name
            })
            ->addColumn('country_name', function(State $state) {
                return $state->country->name; // Display the country name
            })
            ->addColumn('iso2', function(State $state) {
                return $state->iso2;
            })
            ->addColumn('latitude', function(State $state) {
                return number_format( $state->latitude, 4 );
            })
            ->addColumn('longitude', function(State $state) {
                return number_format( $state->longitude, 4 );
            })
            ->addColumn('status', function(State $state) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $state->status == 0 ? 'times' : 'check').' update-status" data-status="'.$state->status.'" data-id="'.$state->id.'" aria-hidden="true" data-table="states"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $state->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$state->id.'" data-table="states">
                            <option value="1" '.($state->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($state->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('updated_at', function(State $state) {
                return formatDate( "Y-m-d H:i", $state->updated_at );
            })
            ->addColumn('action', function(State $state ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$state->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$state->id.'">
                    ';

                    if ($this->user->can('state.edit')) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.state.edit', $state->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($this->user->can('state.delete')) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$state->id.'" data-title="'.$state->name.'" data-segment="state">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'name', 'iso2', 'latitude', 'longitude', 'updated_at', 'continent_name', 'country_name', 'status', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    $query->where('name', 'like', "%{$searchValue}%")
                        ->orWhereHas('continent', function($q) use ($searchValue) {
                            $q->where('name', 'like', "%{$searchValue}%");
                        })
                        ->orWhereHas('country', function($q) use ($searchValue) {
                            $q->where('name', 'like', "%{$searchValue}%");
                        });
                        // ->orWhere('email', 'like', "%{$searchValue}%");
                }
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request('order')[0]['column'];
                    $orderDirection = request('order')[0]['dir'];
                    $columns = request('columns');
                    $query->orderBy($columns[$orderColumn]['data'], $orderDirection);
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('state.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create State !');
        }

        $countryArr = Country::get();
        $continentArr = Continent::get();
        return view('backend.pages.states.create', compact( 'countryArr', 'continentArr' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('state.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create State !');
        }

        // Validation Data
        $request->validate([
            'continent_id' => 'required',
            'country_id' => 'required',
            'name' => 'required|max:20',
            'fips_code' => 'required',
            'iso2' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Create New Server Record
        $dataObj = new State();
        $dataObj->continent_id = $request->continent_id;
        $dataObj->country_id = $request->country_id;
        $dataObj->name = $request->name;
        $dataObj->fips_code = $request->fips_code;
        $dataObj->iso2 = $request->iso2;
        $dataObj->longitude = $request->longitude;
        $dataObj->latitude = $request->latitude;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', $dataObj->name.' record has been created !!');
        return redirect()->route('admin.state.index');
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
        if (is_null($this->user) || !$this->user->can('state.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit State !');
        }

        $data = State::find($id);
        $countryArr = Country::get();
        $continentArr = Continent::get();
        return view('backend.pages.states.edit', compact('data', 'countryArr', 'continentArr'));
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
        if (is_null($this->user) || !$this->user->can('state.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit State !');
        }

        // Validation Data
        $request->validate([
            'continent_id' => 'required',
            'country_id' => 'required',
            'name' => 'required|max:20',
            'fips_code' => 'required',
            'iso2' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Create New Server Record
        $dataObj = State::find( $id );
        $dataObj->continent_id = $request->continent_id;
        $dataObj->country_id = $request->country_id;
        $dataObj->name = $request->name;
        $dataObj->fips_code = $request->fips_code;
        $dataObj->iso2 = $request->iso2;
        $dataObj->longitude = $request->longitude;
        $dataObj->latitude = $request->latitude;
        $dataObj->status = $request->status;
        $dataObj->save();

        session()->flash('success', $dataObj->name.' records has been updated !!');
        return redirect()->route('admin.state.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('state.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete State !');
        }

        $dataObj = State::find($id);
        if ( $dataObj ) {
            $dataObj->delete();
            return response()->json( ['data' => ['message' =>  $dataObj->name.' record has been successfully deleted.'] ], 200 );
        } else {
            return response()->json( ['data' => ['message' => 'Record already deleted.'] ], 200);
        }
    }
}
