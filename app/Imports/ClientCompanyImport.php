<?php
namespace App\Imports;

use App\Models\AccountManagememt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClientCompanyImport implements ToModel, WithStartRow
{
    protected $company_id;

    // Constructor to accept additional variables
    public function __construct( int $company_id )
    {
        $this->company_id = $company_id;
    }

    public function startRow(): int
    {
        return 6; // Skip metadata, start from actual data
    }

    /**
     * 0 => "1"
     * 1 => "PVL01"
     * 2 => "Opening Balance"
     * 3 => "PVL Tours and Travels PVT LTD."
     * 4 => "Active"
     */
    public function model(array $row)
    {
        /**
         * check Client Company exist
         */
            $clientCompanyObj = AccountManagememt::where('code', $row[1] )->first();
            if( !$clientCompanyObj ){
                return new AccountManagememt([
                    'company_id' => $this->company_id,
                    'name' => $row[2] ?? null,
                    'code' => $row[1] ?? 0.00,
                    'status' => ( $row[4] == "Active" ) ? 1 : 0,
                ]);
            }
        return true;
    }
}
