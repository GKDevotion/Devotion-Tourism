<?php
namespace App\Imports;

use App\Models\Accounting;
use App\Models\AccountManagememt;
use App\Models\Admin;
use App\Models\BankInformation;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccountSummaryImport implements ToModel, WithStartRow
{
    protected $company_id;
    protected $user;

    // Constructor to accept additional variables
    public function __construct( int $company_id )
    {
        $this->company_id = $company_id;
    }

    public function startRow(): int
    {
        return 3; // Skip metadata, start from actual data
    }

    /**
     * 0 => "Serial Number"
     * 1 => "Date"
     * 2 => "Username"
     * 3 => "Payment Type"
     * 4 => "Company Code"
     * 5 => "Company Name"
     * 6 => "Description"
     * 7 => "Credit"
     * 8 => "Debit"
     * 9 => "Balance"
     * 10 => "Credit"
     * 11 => "Debit"
     * 12 => "Balance"
     */
    public function model(array $row)
    {
        dd($row);
        /**
         * check admin user exist
         */
            $adminObj = Admin::where('username', $row[2] )->first();
            if( !$adminObj ){
                $adminObj = new Admin();
                $adminObj->username = $row[2];
                $adminObj->company_id = $this->company_id;
                $adminObj->industry_id = 87;
                $adminObj->admin_user_group_id = 3;
                $adminObj->first_name = $row[2];
                $adminObj->email = $row[2]."@mailinator.com";
                $adminObj->password = Hash::make( $row[2] );
                $adminObj->save();
            }

        /**
         * check Client Company exist
         */
            $clientCompanyObj = AccountManagememt::where('code', $row[4] )->first();
            if( !$clientCompanyObj ){
                $clientCompanyObj = new AccountManagememt();
                $clientCompanyObj->code = $row[4];
                $clientCompanyObj->company_id = $this->company_id;
                $clientCompanyObj->name = $row[4];
                $clientCompanyObj->save();
            }

        /**
         * check Bank Information exist
         */
            $bankInformationObj = BankInformation::where('bank_name', $row[3] )->first();
            if( !$bankInformationObj ){
                $bankInformationObj = new BankInformation();
                $bankInformationObj->admin_id = $adminObj->id;
                $bankInformationObj->company_id = $this->company_id;
                $bankInformationObj->bank_name = $row[3];
                $bankInformationObj->holder_name = $row[3];
                $bankInformationObj->save();
            }

        return new Accounting([
            'admin_id' => $adminObj->id,
            'company_id' => $$this->company_id,
            'txn_no' => $row[0] ?? null,
            'debit_amount' => $row[8] ?? 0.00,
            'credit_amount' => $row[7] ?? 0.00,
            'balance' => 0,
            'description' => $row[6] ?? null,
            'company_code' => $clientCompanyObj->id,
            'payment_type' => $bankInformationObj->id,
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1] ?? null)->format('Y-m-d'),
            'is_check_balance' => 0,
        ]);
    }
}
