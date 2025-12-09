<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use App\Models\AccountManagememt;
use App\Models\AccountSummeryFileMaps;
use App\Models\Admin;
use App\Models\BankInformation;
use App\Models\Company;
use App\Models\Currency;
use App\Services\FileRepositoryService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function redirectAdmin()
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get Companies:
     * https://api.devotionreport.com/Company/GetCompanies?page=1&pageSize=10&sortingDirection=asc
     * https://drs.shreegurve.com/get-devotion-companies?page=1
     */
    public function getCompanies( Request $request ){

        $page = $request->page ?? 1;
        $url = "https://api.devotionreport.com/Company/GetCompanies?page=".$page."&pageSize=50&sortingDirection=asc";
        $resultArr = $this->callCURLFunction( $url );

        if( COUNT( $resultArr ) >0 && $resultArr['totalRecords'] > 0 ){
            foreach( $resultArr['getCompanyList'] as $res ){

                $dataObj = Company::find( $res['id'] );

                if( !$dataObj ){
                    $dataObj = new Company();
                    $dataObj->id = $res['id'];
                    $dataObj->admin_id = 1;
                }

                $companyName = $res['companyName'];
                $dataObj->name = $companyName;
                $dataObj->website_link = $companyName;
                $dataObj->industry_id = 87;
                $dataObj->status = $res['isActive'] ? 1 : 0;
                $dataObj->parent_id = 0;
                $dataObj->sort_order = $res['index'];

                $words = explode( " ", trim( preg_replace( '/\s+/', ' ', $companyName ) ) );
                $initials = "";
                foreach ($words as $word) {
                    $initials .= strtoupper($word[0]); // uppercase first letter
                }
                $dataObj->sort_name = $initials;
                $dataObj->email_id = $res['emailId'];
                $dataObj->contact_number = $res['contactNumber'];
                $dataObj->slug = convertStringToSlug($companyName);

                //get currency ids
                $currencyArr = Currency::whereIn( 'name', explode( ',', trim( $res['currency'] ) ) )->pluck( 'id' );
                $dataObj->currency_id = json_encode( $currencyArr, 1 );
                $dataObj->save();
            }
        }
    }

    /**
     * Get Company Base Clients:
     * https://api.devotionreport.com/Company/GetCompanyOrAccounts?companyId=1&page=1&pageSize=10&sortingDirection=asc
     * https://drs.shreegurve.com/get-devotion-company-base-client
     */
    public function getCompanBaseClient( Request $request ){

        $pageSize = 100;

        // Get Company List
        $companyObj = Company::select('id', 'name')->get();

        foreach ($companyObj as $ar) {
            echo "<br>" . $ar->name . "<br>";

            $page = 1; // Reset page per company
            $continue = true;

            do {
                $url = "https://api.devotionreport.com/Company/GetCompanyOrAccounts?companyId=" . $ar->id . "&page=" . $page . "&pageSize=" . $pageSize . "&sortingDirection=asc";

                echo $url . "<br><br>"; // For debugging

                $resultArr = $this->callCURLFunction($url);

                if (isset($resultArr['totalRecords']) && $resultArr['totalRecords'] > 0 && isset($resultArr['clients']) && count($resultArr['clients']) > 0) {
                    foreach ($resultArr['clients'] as $res) {
                        $dataObj = AccountManagememt::find($res['id']); // Assuming this is the correct model

                        if (!$dataObj) {
                            $dataObj = new AccountManagememt(); // Correct model
                            $dataObj->id = $res['id'];
                            $dataObj->admin_id = 1;
                            $dataObj->company_id = $ar->id;
                        }

                        $dataObj->name = $res['clientName'] ?? '';
                        $dataObj->status = $res['isActive'] ? 1 : 0;
                        $dataObj->code = $res['clientAccountNumber'] ?? '';
                        $dataObj->save();
                    }

                    $page++;
                } else {
                    $continue = false; // Stop loop if no more records
                }

            } while ($continue);
        }
    }

    /**
     * Get Company Base Bank Accounts:
     * https://api.devotionreport.com/Company/GetBankAccounts?companyId=1&page=1&pageSize=10&sortingDirection=asc
     * https://drs.shreegurve.com/get-devotion-company-base-bank-accounts
     */
    public function getCompanBaseBankAccount( Request $request ){

        $pageSize = 100;

        // Get Company List
        $companyObj = Company::select('id', 'name')->get();

        foreach ($companyObj as $ar) {
            echo "<br>" . $ar->name . "<br>";

            $page = 1; // Reset page per company
            $continue = true;

            do {
                $url = "https://api.devotionreport.com/Company/GetBankAccounts?companyId=" . $ar->id . "&page=" . $page . "&pageSize=" . $pageSize . "&sortingDirection=asc";

                echo $url . "<br><br>"; // For debugging

                $resultArr = $this->callCURLFunction($url);

                if (isset($resultArr['totalRecords']) && $resultArr['totalRecords'] > 0 && isset($resultArr['bankAccounts']) && count($resultArr['bankAccounts']) > 0) {
                    foreach ($resultArr['bankAccounts'] as $res) {
                        $dataObj = BankInformation::find($res['id']); // Assuming this is the correct model

                        if (!$dataObj) {
                            $dataObj = new BankInformation(); // Correct model
                            $dataObj->id = $res['id'];
                            $dataObj->admin_id = 1;
                            $dataObj->company_id = $ar->id;
                            $dataObj->currency_id = 1;
                        }

                        $dataObj->bank_name = $res['bankName'] ?? '';
                        $dataObj->slug = convertStringToSlug( $res['bankName'] ?? '' );
                        $dataObj->holder_name = $res['bankAccountName'] ?? '';
                        $dataObj->account_number = $res['accountNumber'];
                        $dataObj->ifsc_code = "";
                        $dataObj->iban = $res['iban'];
                        $dataObj->branch_code = $res['branchCode'];
                        $dataObj->description = $res['description'];
                        $dataObj->status = $res['isActive'] ? 1 : 0;
                        $dataObj->save();
                    }

                    $page++;
                } else {
                    $continue = false; // Stop loop if no more records
                }

            } while ($continue);
        }
    }

    /**
     * Get Company Base Accounts:
     * https://api.devotionreport.com/Company/GetAccountSummary?companyId=1&page=1&pageSize=10&sortingDirection=asc
     * https://drs.shreegurve.com/get-devotion-company-base-account-summery?companyId=1&page=1&pageSize=100&sortingDirection=asc
     * http://127.0.0.1/laravel/devotion-finance-services/get-devotion-company-base-account-summery?companyId=5&page=1&pageSize=1&sortingDirection=asc
     */
    public function getCompanBaseAccountSummery( Request $request ){

        $pageSize = $request->pageSize ?? 100;

        // Get Company List
        $url = "https://api.devotionreport.com/Company/GetAccountSummary?companyId=" . $request->companyId . "&page=" . $request->page . "&pageSize=" . $pageSize . "&sortingDirection=asc";

        $resultArr = $this->callCURLFunction($url);

        $userArr = [
            'Super' => 1,
            'Kamran' => 6,
            'Munaf' => 14,
            'Ashish' => 12,
            'Sumit' => 10,
            'Sharmishtha' => 11,
            'Vishal' => 13,
            'General' => 8,
            'Chetan' => 9,
            'chetan12' => 15
        ];

        if (isset($resultArr['totalRecords']) && $resultArr['totalRecords'] > 0 && isset($resultArr['accountSummary']) && count($resultArr['accountSummary']) > 0) {
            foreach ($resultArr['accountSummary'] as $res) {
                $dataObj = Accounting::where( [
                    'txn_no' => $res['SerialNumber'],
                    'company_id' => $request->companyId,
                ] )
                ->first(); // Assuming this is the correct model

                if (!$dataObj) {
                    $dataObj = new Accounting(); // Correct model
                    $dataObj->txn_no = $res['SerialNumber'];
                    $dataObj->company_id = $request->companyId;

                    $admin_id = 1;
                    // $adminObj = Admin::where('username', trim($res['Username']))->select('id')->first();
                    // if( $adminObj ){
                    //     $admin_id = $adminObj->id;
                    // }

                    $keyWords = explode(' ', trim($res['Username']) );
                    $matchedValue = null;

                    foreach ($keyWords as $word) {
                        if (array_key_exists($word, $userArr)) {
                            $matchedValue = $userArr[$word];
                            break;
                        }
                    }

                    if ($matchedValue !== null) {
                        $admin_id = $matchedValue;
                    }
                    $dataObj->admin_id = $admin_id;
                }

                $dataObj->status = 1;
                $dataObj->debit_amount = $res['Debit Amount'];
                $dataObj->credit_amount = $res['Credit Amount'];
                $dataObj->balance = 0;
                $dataObj->save();

                //check filename name exists
                if( isset( $res['FileName'] ) && $res['FileName'] != "" ){

                    $fileName = explode( ".", $res['FileName'] );// preg_replace('/-(?=[^-]*$)/', '.', convertStringToSlug( $res['FileName'] ) );
                    $folderName = "account-summery/".$request->companyId;
                    $repo = new FileRepositoryService('public');
                    $dataObj->document = 1;
                    //"public/".$repo->saveBinaryWithUniqueName( $folderName, base64_decode( $res['FileBinary'] ), $request->companyId.'-'.$res['SerialNumber'].".".end( $fileName ) );

                    $fileMap = new AccountSummeryFileMaps();
                    $fileMap->account_summery_id = $dataObj->id;
                    $fileMap->company_id = $request->companyId;
                    $fileMap->txn_no = $dataObj->txn_no;
                    $fileMap->indexing = 0;
                    $fileMap->path = "public/".$repo->saveBinaryWithUniqueName( $folderName, base64_decode( $res['FileBinary'] ), $request->companyId.'-'.$res['SerialNumber'].".".end( $fileName ) );
                    $fileMap->filename = $res['FileName'];
                    $fileMap->status = 1;
                    $fileMap->save();
                }

                $crmUpdate = 0;
                if( isset( $res['CRM Update'] ) )
                {
                    if( $res['CRM Update'] == "No" ){
                        $crmUpdate = 0;
                    } else {
                        $crmUpdate = 1;
                    }
                }

                $dataObj->crm_update = $crmUpdate;
                $dataObj->description = $res['Description'];
                $dataObj->remarks = $res['Remarks'];

                //get payment type id
                $company_code = 0;
                $companyCodeObj = AccountManagememt::where( [
                    'code' => $res['Company Code'],
                    'company_id' => $res['CompanyId']
                ] )
                ->select('id')
                ->first();

                if( $companyCodeObj ){
                    $company_code = $companyCodeObj->id;
                }

                $dataObj->company_code = $company_code;

                //get payment type id
                $paymentType = 0;
                $paymentTypeObj = BankInformation::where( [
                    'bank_name' => $res['Payment Type'],
                    'company_id' => $res['CompanyId']
                ] )
                ->select('id')
                ->first();

                if( $paymentTypeObj ){
                    $paymentType = $paymentTypeObj->id;
                }

                $dataObj->payment_type = $paymentType;
                $dataObj->date = formatDate( "Y-m-d", $res['convertedDate'] );
                $dataObj->is_check_balance = 0;
                $dataObj->save();

            }

            $URL = url( 'get-devotion-company-base-account-summery?companyId='.$request->companyId.'&page='.($request->page + 1). '&pageSize='.$pageSize.'&sortingDirection=asc' );
            echo "Next URL: ".$URL."<br>";

            echo '<script>
                setTimeout(function(){
                    window.location.href = "'.$URL.'";
                }, 5000);
            </script>';
        } else {
            echo "Complete All record for ID: ".$request->companyId;
            updateAccountSummeryBalance( $request->companyId );//// To recursively fetch and update one record at a time in Laravel until no more records are left, you can use a while loop.
        }
    }

    /**
     * Get Company Base Accounts:
     * https://api.devotionreport.com/Company/GetAccountSummary?companyId=1&page=1&pageSize=10&sortingDirection=asc
     *
     * http://127.0.0.1/laravel/devotion-finance-services/get-devotion-company-base-account-summery?companyId=5&page=1&pageSize=1&sortingDirection=asc
     */
    public function updateAccountSummeryAdmin( Request $request ){

        $pageSize = $request->pageSize ?? 100;

        // Get Company List
        $url = "https://api.devotionreport.com/Company/GetAccountSummary?companyId=" . $request->companyId . "&page=" . $request->page . "&pageSize=" . $pageSize . "&sortingDirection=asc";

        $resultArr = $this->callCURLFunction($url);

        $userArr = [
            'Super' => 1,
            'Kamran' => 6,
            'Munaf' => 14,
            'Ashish' => 12,
            'Sumit' => 10,
            'Sharmishtha' => 11,
            'Vishal' => 12,
            'General' => 8,
            'Chetan' => 9,
            'chetan12' => 15
        ];

        if (isset($resultArr['totalRecords']) && $resultArr['totalRecords'] > 0 && isset($resultArr['accountSummary']) && count($resultArr['accountSummary']) > 0) {
            foreach ($resultArr['accountSummary'] as $res) {
                $dataObj = Accounting::where( [
                    'txn_no' => $res['SerialNumber'],
                    'company_id' => $request->companyId,
                ] )
                ->first(); // Assuming this is the correct model

                if ($dataObj) {

                    $admin_id = 1;
                    $keyWords = explode(' ', trim($res['Username']) );
                    $matchedValue = null;

                    foreach ($keyWords as $word) {
                        if (array_key_exists($word, $userArr)) {
                            $matchedValue = $userArr[$word];
                            break;
                        }
                    }

                    if ($matchedValue !== null) {
                        $admin_id = $matchedValue;
                    }

                    // dd( $userArr, $keyWords, $matchedValue, $admin_id );
                    $dataObj->admin_id = $admin_id;
                    $dataObj->save();
                }
            }

            $URL = url( 'update-account-summery-admin?companyId='.$request->companyId.'&page='.($request->page + 1). '&pageSize='.$pageSize.'&sortingDirection=asc' );
            echo "Next URL: ".$URL."<br>";

            echo '<script>
                setTimeout(function(){
                    window.location.href = "'.$URL.'";
                }, 5000);
            </script>';
        } else {
            echo "Complete All record for ID: ".$request->companyId;
        }
    }

    /**
     * Get Company Base Accounts:
     * https://api.devotionreport.com/Company/GetAccountSummary?companyId=1&page=1&pageSize=10&sortingDirection=asc
     *
     * http://127.0.0.1/laravel/devotion-finance-services/get-account-summery-documents?companyId=5&page=1&pageSize=1&sortingDirection=asc
     *
     * https://drs.shreegurve.com/get-account-summery-documents?companyId=11&page=99&pageSize=50&sortingDirection=asc
     */
    public function getAccountSummeryDocuments( Request $request ){

        $pageSize = $request->pageSize ?? 100;

        // Get Company List
        $url = "https://api.devotionreport.com/Company/GetAccountSummary?companyId=" . $request->companyId . "&page=" . $request->page . "&pageSize=" . $pageSize . "&sortingDirection=asc";

        $resultArr = $this->callCURLFunction($url);

        if (isset($resultArr['totalRecords']) && $resultArr['totalRecords'] > 0 && isset($resultArr['accountSummary']) && count($resultArr['accountSummary']) > 0) {
            foreach ($resultArr['accountSummary'] as $res) {
                $dataObj = Accounting::where( [
                    'txn_no' => $res['SerialNumber'],
                    'company_id' => $request->companyId,
                ] )
                ->first(); // Assuming this is the correct model

                if ($dataObj) {
                    if( isset( $res['FileName'] ) && $res['FileName'] != "" ){
                        $fileName = explode( ".", $res['FileName'] );// preg_replace('/-(?=[^-]*$)/', '.', convertStringToSlug( $res['FileName'] ) );
                        $folderName = "account-summery";
                        $repo = new FileRepositoryService('public');
                        $dataObj->document = "public/".$repo->saveBinaryWithUniqueName( $folderName, base64_decode( $res['FileBinary'] ), $request->companyId.'-'.$res['SerialNumber'].".".end( $fileName ) );
                        $dataObj->save();
                    }
                }
            }

            $URL = url( 'get-account-summery-documents?companyId='.$request->companyId.'&page='.($request->page + 1). '&pageSize='.$pageSize.'&sortingDirection=asc' );
            echo "Next Call URL: ".$URL."<br>";

            echo '<script>
                setTimeout(function(){
                    window.location.href = "'.$URL.'";
                }, 5000);
            </script>';
        } else {
            echo "Complete All record for ID: ".$request->companyId;
        }
    }

    /**
     * common CURL function
     */
    public function callCURLFunction( $url ){

        $resultArr = [];
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);              // Set the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // Return the response as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   // Follow redirects

        curl_setopt($ch, CURLOPT_ENCODING, '' );
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_TIMEOUT, 0 );
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Execute the request
        $response = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            // Output the response
            $resultArr = json_decode( $response, 1 );
        }

        return $resultArr;
    }

    /**
     * Get Company Base Bank Accounts:
     * https://api.devotionreport.com/Company/GetBankAccounts?companyId=1&page=1&pageSize=10&sortingDirection=asc
     */
    public function updateBankNameToSlug( Request $request ){

        $bankObj = BankInformation::select( 'id', 'bank_name', 'slug' )->get();
        foreach( $bankObj as $bnk ){
            $bnk->slug = convertStringToSlug( $bnk->bank_name );
            $bnk->save();
        }
    }
}
