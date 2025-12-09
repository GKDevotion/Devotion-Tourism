<?php

namespace App\Exports;

use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClientsExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $type = 3;// 1: Employee, 2: Customer, 3: Client
    protected $download_id;
    protected $sheet_name;
    protected $user;
    protected $sheetName;
    protected $download_type = "";

    public function __construct( $download_id, $sheet_name, $download_type="" )
    {
        $this->download_id = $download_id;
        $this->sheetName = $sheet_name;
        $this->download_type = $download_type;

        $this->user = Auth::guard('admin')->user();
    }

    // Fetch data based on table
    public function query()
    {
        if( $this->download_id ){
            $where['type'] = $this->type;

            if( $this->download_type == "city" ){
                $where['city_id'] = $this->download_id;
            } else if( $this->download_type == "state" ){
                $where['state_id'] = $this->download_id;
            } else if( $this->download_type == "country" ){
                $where['country_id'] = $this->download_id;
            } else {
                $where['continent_id'] = $this->download_id;
            }
            
            if( !$this->user->is_assign_super_admin ){
                $where['admin_id'] = $this->user->id;
            }

            return Person::query()->where( $where );
        }
        
        return collect();  // Return an empty collection if no matching table
    }

    // Map fields to be shown in Excel
    public function map($row): array
    {
        $gender = [
            1 => 'Male',
            2 => 'Fe Male',
            3 => 'Trans'
        ];

        return [
            $row->unique_id,
            $row->first_name." ".$row->middle_name." ".$row->last_name,
            $row->email_id,
            $row->personal_mobile_number,
            $gender[$row->gender],
            $row->industry->name,
            $row->business ? $row->business->name : '',
            $row->religion ? $row->religion->name : '',
            $row->country ? $row->country->name : '',
            $row->state ? $row->state->name : '',
            $row->city ? $row->city->name : '',
            $row->birth_date,
            $row->business->unique_id,
            $row->business->name,
            $row->business->email_id,
            $row->business->website,
            $row->business->contact_number,
            $row->business->established_date,
            $row->bussiness_address->address,
            $row->bussiness_address->country ? $row->bussiness_address->country->name : '',
            $row->bussiness_address->state ? $row->bussiness_address->state->name : '',
            $row->bussiness_address->city ? $row->bussiness_address->city->name : '',
            $row->bussiness_address->zipcode
        ];
    }

    // Define Excel headings
    public function headings(): array
    {
        return ['Unique ID', 'Name', 'Email ID', 'Mobile Number', 'Gender', 'Industry', 'Business', 'Religion', 'Country', 'State', 'City', 'Birth Date', 'Business ID', 'Business Name', 'Business Email', 'Business Website', 'Business Number', 'Business Established', 'Business Address', 'Business Country', 'Business State', 'Business City', 'Business Zipcode'];
    }

    /**
     * 
     */
    public function title(): string
    {
        return $this->sheetName." data";
    }
}
