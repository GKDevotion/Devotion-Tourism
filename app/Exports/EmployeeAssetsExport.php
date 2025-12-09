<?php

namespace App\Exports;

use App\Models\EmployeeAssetDatas;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeeAssetsExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $user;
 
    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }

    // Fetch data based on table
    public function query()
    {
        $where = [];
        if( !$this->user->is_assign_super_admin ){
            $where['admin_id'] = $this->user->id;
        }

        return EmployeeAssetDatas::query()->where( $where );
        
        return collect();  // Return an empty collection if no matching table
    }

    // Map fields to be shown in Excel
    public function map($row): array
    {
        return [
            $row->id,
            $row->person->unique_id,
            $row->person->first_name." ".$row->person->middle_name." ".$row->person->last_name,
            $row->person->department->name,
            $row->person->company_mobile_number,
            $row->person->personal_mobile_number,
            $row->person->birth_date,
            $row->person->joining_date,
            $row->person->asset_no,
            $row->sim->number."( ".$row->sim->name." )",
            $row->sim->serial_number,
            $row->sim_given_date,
            $row->mobile->name."( ".$row->mobile->brand." )",
            "IMEI: ".$row->mobile->imei.", SN: ".$row->mobile->sn.", MEID: ",$row->mobile->meid,
            $row->mobile_given_date,
            $row->laptop->name."( ".$row->laptop->brand." )",
            "RNM: ".$row->laptop->rnm.", Product ID: ".$row->laptop->product_id.", SN: ",$row->laptop->sn.", Model: ",$row->laptop->model,
            $row->laptop_given_date,
            $row->other_assets,
            $row->general_assets,
            $row->notes
        ];
    }

    // Define Excel headings
    public function headings(): array
    {
        return ['No.', 'Employee Number', 'Employee Name', 'Department', 'Company No.', 'Personal No.', 'Birthday', 'Start Day', 'Asset Form Number', 'Sim', 'Sim Series Number', 'Date Given', 'Mobile', 'Mobile Details', 'Date Given', 'Laptop', 'Laptop Details', 'Date Given', 'Other Assets', 'General Assets', 'Notes'];
    }

    /**
     * 
     */
    public function title(): string
    {
        // return $this->sheetName." data";
        return "Employee Assets data";
    }
}
