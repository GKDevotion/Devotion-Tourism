<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClientsSelectedIndustryBaseExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $user;
    protected $filters;

    public function __construct( $filters )
    {
        $this->user = Auth::guard('admin')->user();
        $this->filters = $filters;
    }

    // Fetch data based on table
    public function query()
    {
        $where = [];
        if( !$this->user->is_assign_super_admin ){
            $where['admin_id'] = $this->user->id;
        }

        if( $this->filters['client_company'] > 0 ){
            $where['company_id'] = $this->filters['client_company'];
        }

        if( $this->filters['client_company'] > 0 ){
            $where['company_id'] = $this->filters['client_company'];
        }

        if( $this->filters['type'] > 0 ){
            $where['type'] = $this->filters['type'];
        }

        return Client::query()->where( $where );
        
        return collect();  // Return an empty collection if no matching table
    }

    // Map fields to be shown in Excel
    public function map($row): array
    {
        if( $this->filters['type'] == 1 ){
            return [
                $row->id,
                $row->first_name." ".$row->middle_name." ".$row->last_name,
                $row->email_id,
                $row->mobile_number,
                strip_tags( $row->client_address->address ).", ".$row->client_address->continent->name.", ".$row->client_address->country->name.", ".$row->client_address->state->name.", ".$row->client_address->city->name.", ".$row->client_address->zipcode,
                $row->corporate_user->name,
                $row->corporate_user->email_id,
                $row->corporate_user->website,
                $row->corporate_user->mobile_number,
                $row->corporate_user->industry->name,
                strip_tags( $row->corporate_user->address ).", ".$row->corporate_user->continent->name.", ".$row->corporate_user->country->name.", ".$row->corporate_user->state->name.", ".$row->corporate_user->city->name.", ".$row->corporate_user->zipcode,
                $row->social_media,
                strip_tags( $row->other_info )
            ];
        } else if( $this->filters['type'] == 1 ){
            return [
                $row->id,
                $row->first_name." ".$row->middle_name." ".$row->last_name,
                $row->email_id,
                $row->mobile_number,
                strip_tags( $row->client_address->address ).", ".$row->client_address->continent->name.", ".$row->client_address->country->name.", ".
                $row->employee_user->job_title,
                $row->employee_user->company_name,
                $row->employee_user->company_website,
                $row->employee_user->company_email,
                $row->employee_user->company_number,
                strip_tags( $row->employee_user->address ).", ".$row->employee_user->continent->name.", ".$row->employee_user->country->name.", ".$row->employee_user->state->name.", ".$row->employee_user->city->name.", ".$row->employee_user->zipcode,
                $row->social_media,
                strip_tags( $row->other_info )
            ];
        }
        
    }

    // Define Excel headings
    public function headings(): array
    {
        if( $this->filters['type'] == 1 ){
            return ['ID', 'Name', 'Email', 'Mobile Number', 'Address', 'Business Name', 'Business Email', 'Business Website', 'Business Number', 'Business Industry', 'Business Address', 'Social Media', 'Other Information'];
        } else if( $this->filters['type'] == 2 ){
            return ['ID', 'Name', 'Email', 'Mobile Number', 'Address', 'Job Title', 'Company Name', 'Company Website', 'Company Email', 'Company Number', 'Address', 'Social Media', 'Other Information'];
        }
    }

    /**
     * 
     */
    public function title(): string
    {
        $sheetName = "";
        if( $this->filters['client_company'] > 0 ){
            $sheetName = getIdBaseValue( "companies", [ 'id' => $this->filters['client_company'] ], 'name' );
        } else if( $this->filters['client_industry'] > 0 ){
            $sheetName = getIdBaseValue( "industries", [ 'id' => $this->filters['client_industry'] ], 'name' );
        }

        return ucfirst( $sheetName )." data";
    }
}
