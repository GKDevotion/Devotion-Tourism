<?php

namespace App\Imports;

use App\Models\Address;
use App\Models\Business;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Industry;
use App\Models\Person;
use App\Models\Religion;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
{
    public $personType = 3;// 1: Employee, 2: Customer, 3: Client

    /**
     * 
     */
    public function model(array $row)
    {
        $user = Auth::guard('admin')->user();

        //creat new client or person
        $personDataObj = new Person();

        $personDataObj->admin_id = $user->id;
        $personDataObj->user_id = $user->id;

        //get Industry records
        $industryObj = Industry::where( 'name', 'like', '%'.$row['industry'].'%' )->first();

        $industry_id = 0;
        if( $industryObj ){
            $industry_id = $industryObj->id;
        }
        
        $personDataObj->industry_id = $industry_id;
        $personDataObj->unique_id = date('ymd').appendDynamicUniqueNumber( $this->personType, 2 );//1: Employee, 2: Customer, 3: Client	
        $personDataObj->asset_no = null;
        $personDataObj->first_name = $row['first_name'];
        $personDataObj->middle_name = $row['middle_name'];
        $personDataObj->last_name = $row['last_name'];
        $personDataObj->email_id = $row['email_id'];
        $personDataObj->personal_mobile_number = $row['mobile_number'];
        $personDataObj->type = $this->personType;

        //set client gender
        $gender = 3;
        if( strtolower( $row['gender'] ) == "m" || strtolower( $row['gender'] ) == "male" ){
            $gender = 1;
        } else if( strtolower( $row['gender'] ) == "f" || strtolower( $row['gender'] ) == "fe male" || strtolower( $row['gender'] ) == "female" ){
            $gender = 2;
        }
        $personDataObj->gender = $gender;

        //get Religion records
        $religionObj = Religion::where( 'name', 'like', '%'.$row['religion'].'%' )->first();

        if( $religionObj ){
            $personDataObj->religion_id = $religionObj->id;
        } else {
            $personDataObj->religion_id = 0;
        }

        $personDataObj->joining_date = $row['meet_date'] ?? null;
        $personDataObj->other_info = $row['other_information'] ?? null;
        $personDataObj->social_medias = null;
        $personDataObj->save();

            //save person addresses
            $personAddressObj = new Address();
            $personAddressObj->admin_id = $user->id;
            $personAddressObj->person_id = $personDataObj->id;
            $personAddressObj->address = $row['address'] ?? null;
            $personAddressObj->unique_id = $row['business_id'];
            $personAddressObj->name = $row['first_name']." ".$row['middle_name']." ".$row['last_name'];
            $personAddressObj->email_id = $row['email_id'];
            $personAddressObj->contact_number = $row['mobile_number'];

            //get Continent records
            $continentObj = Continent::where( 'name', 'like', '%'.$row['continent'].'%' )->first();

            if( $continentObj ){
                $personDataObj->continent_id = $continentObj->id;
            } else {
                $personDataObj->continent_id = 0;
            }

            //get Country records
            $countryObj = Country::where( 'name', 'like', '%'.$row['country'].'%' )->first();

            if( $countryObj ){
                $personDataObj->country_id = $countryObj->id;
            } else {
                $personDataObj->country_id = 0;
            }

            //get State records
            $stateObj = State::where( 'name', 'like', '%'.$row['state'].'%' )->first();

            if( $stateObj ){
                $personDataObj->state_id = $stateObj->id;
            } else {
                $personDataObj->state_id = 1;
            }

            //get Industry records
            $cityObj = City::where( 'name', 'like', '%'.$row['city'].'%' )->first();

            if( $cityObj ){
                $personDataObj->city_id = $cityObj->id;
            } else {
                $personDataObj->city_id = 0;
            }

            $personAddressObj->zipcode = $row['zipcode'];
            $personAddressObj->type = 1;// 1: Permanent, 2: Temporary
            $personAddressObj->description = null;
            $personAddressObj->save();

        //save business information

        $personBusinessObj = new Business();
        $personBusinessObj->admin_id = $user->id;
        $personBusinessObj->person_id = $personDataObj->id;
        $personBusinessObj->unique_id = $row['business_id'];
        $personBusinessObj->industry_id = $industry_id;
        $personBusinessObj->name = $row['business_name'];
        $personBusinessObj->email_id = $row['business_email'];
        $personBusinessObj->website = $row['business_website'];
        $personBusinessObj->contact_number = $row['business_number'];
        $personBusinessObj->description = $row['business_detail'];
        $personBusinessObj->establish_date = $row['business_established'];
        $personBusinessObj->save();
        
        $personDataObj->business_id = $personBusinessObj->id;
        $personDataObj->save();

        //save client business address
        $personAddressObj = new Address();
        $personAddressObj->admin_id = $user->id;
        $personAddressObj->person_id = $personDataObj->id;
        $personAddressObj->unique_id = $row['business_id'];
        $personAddressObj->name = $row['business_name'];
        $personAddressObj->email_id = $row['business_email'];
        $personAddressObj->contact_number = $row['business_number'];
        $personAddressObj->address = $row['business_address'];

        //get Continent records
        $continentObj = Continent::where( 'name', 'like', '%'.$row['business_continent'].'%' )->first();

        if( $continentObj ){
            $personAddressObj->continent_id = $continentObj->id;
        } else {
            $personAddressObj->continent_id = 0;
        }

        //get Country records
        $countryObj = Country::where( 'name', 'like', '%'.$row['business_country'].'%' )->first();

        if( $countryObj ){
            $personAddressObj->country_id = $countryObj->id;
        } else {
            $personAddressObj->country_id = 0;
        }

        //get State records
        $stateObj = State::where( 'name', 'like', '%'.$row['business_state'].'%' )->first();

        if( $stateObj ){
            $personAddressObj->state_id = $stateObj->id;
        } else {
            $personAddressObj->state_id = 1;
        }

        //get Industry records
        $cityObj = City::where( 'name', 'like', '%'.$row['business_city'].'%' )->first();

        if( $cityObj ){
            $personAddressObj->city_id = $cityObj->id;
        } else {
            $personAddressObj->city_id = 0;
        }

        $personAddressObj->zipcode = $row['business_zipcode'];
        $personAddressObj->type = 3;//Business
        $personAddressObj->description = null;
        $personAddressObj->save();
    }
}