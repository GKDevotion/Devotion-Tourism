<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ReligionResource;
use App\Http\Resources\StateResource;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Religion;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class ContinentController extends BaseController
{

    /**
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        //
    }

    /**
     *
     */
    public function getCountryByContinentID( $continent_id = null ){
        if( $continent_id ){
            $countryObj = Country::with('continent')->where( [ 'continent_id' => $continent_id, 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
        } else {
            $countryObj = Country::with('continent')->orderBy( 'name', 'ASC' )->get();
        }
        return $this->sendResponse(CountryResource::collection($countryObj), 'Records retrieved successfully.');
    }

    /**
     *
     */
    public function getStates( ){
        $perPage = request()->input('per_page', 200); // Default to 10 cities per page
        $stateObj = State::with('continent', 'country')->orderBy( 'name', 'ASC' )->paginate($perPage);

        $pagination = [
            'total' => $stateObj->total(),
            'per_page' => $stateObj->perPage(),
            'current_page' => $stateObj->currentPage(),
            'last_page' => $stateObj->lastPage(),
        ];
        return $this->sendResponse(StateResource::collection($stateObj), 'Records retrieved successfully.', $pagination );
    }

    /**
     *
     */
    public function getStateByCountryID( $country_id = null ){
        if( $country_id ){
            $stateObj = State::with('continent', 'country')->where( [ 'country_id' => $country_id, 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
        } else {
            $stateObj = State::with('continent', 'country')->orderBy( 'name', 'ASC' )->get();
        }
        return $this->sendResponse(StateResource::collection($stateObj), 'Records retrieved successfully.');
    }

    /**
     *
     */
    public function getCities(){

        $perPage = request()->input('per_page', 200); // Default to 10 cities per page
        $cityObj = City::orderBy( 'name', 'ASC' )->paginate($perPage);

        $pagination = [
            'total' => $cityObj->total(),
            'per_page' => $cityObj->perPage(),
            'current_page' => $cityObj->currentPage(),
            'last_page' => $cityObj->lastPage(),
        ];
        return $this->sendResponse(CityResource::collection($cityObj), 'Records retrieved successfully.', $pagination);
    }

    /**
     *
     */
    public function getCityByStateByID( $state_id = null ){
        if( $state_id ){
            $cityObj = City::with('continent', 'country', 'state')->where( [ 'state_id' => $state_id, 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
        } else {
            $cityObj = City::with('continent', 'country', 'state')->orderBy( 'name', 'ASC' )->get();
        }
        return $this->sendResponse(CityResource::collection($cityObj), 'Records retrieved successfully.');
    }

    /**
     *
     */
    public function getReligions(){
        $religionObj = Religion::orderBy( 'name', 'ASC' )->get();
        return $this->sendResponse(ReligionResource::collection($religionObj), 'Records retrieved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( int $id): JsonResponse
    {
        $dataObj = Continent::find( $id );
        if( $dataObj ){
            $dataObj->delete();
            return $this->sendResponse([], $dataObj->name.' record has been deleted successfully.');
        }  else {
            return $this->sendResponse([], 'Record already deleted.');
        }
    }
}
