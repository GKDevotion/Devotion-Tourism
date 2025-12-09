<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Company;
use App\Models\CompanyMeeting;
use App\Models\CorporateEmail;
use App\Models\Department;
use App\Models\PersonMeeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Exports\TransactionExport;
use App\Imports\AccountSummaryImport;
use App\Imports\ClientCompanyImport;
use App\Models\AccountManagememt;
use App\Models\BankInformation;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    public $user;
    public $is_assign_super_admin = 0;
    public $admin_id = 0;

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

    }

    /**
     *
     */
    public function downloadClientCompanyCSV( Request $request ){

        // Retrieve request parameters
        $company_id = $request->companyId;

        $query = AccountManagememt::select( 'id', 'company_id', 'name', 'code', 'status' );

        if( $company_id ){
            $query = $query->where( 'company_id', $company_id );
        }

        $result = $query->get();

        $handle = fopen('php://temp', 'w+');
        $header = ['Index', 'Company Code', 'Company Account Name', 'Company', 'Active'];
        fputcsv($handle, $header);

        if( $result ){
            foreach ($result as $k=>$ar) {
                $row = [
                    ( $k+1 ),
                    $ar->code,
                    $ar->name,
                    $ar->company->name,
                    $ar->status == 0 ? 'De-Active' : 'Active',
                ];

                fputcsv($handle, $row);
            }
        }

        rewind($handle);
        $csvOutput = stream_get_contents($handle);
        fclose($handle);

        $filename = $result[0]->company->name.".csv";
        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     *
     */
    public function uploadClientCompanyCSVFile( Request $request ){

        Excel::import( new ClientCompanyImport( $request->company_id ), $request->file('excel_file') );

        return back()->with('success', 'Excel file imported successfully');
    }

    /**
     *
     */
    public function downloadClientCompanyPDF( Request $request ){

        // Retrieve request parameters
        $company_id = $request->companyId;

        $query = AccountManagememt::select( 'id', 'company_id', 'name', 'code', 'status' );

        if( $company_id ){
            $query = $query->where( 'company_id', $company_id );
        }

        $result = $query->get();
        $rowData = [];
        if( $result ){
            foreach ($result as $k=>$ar) {
                $row = [
                    'id' => ( $k+1 ),
                    'code' => $ar->code,
                    'name' => $ar->name,
                    'company' => $ar->company->name,
                    'status' => $ar->status == 0 ? 'De-Active' : 'Active',
                ];

                $rowData[] = $row;
            }
        }

        // Customize data based on parameters
        $data = [
            'title' => $result[0]->company->name,
            'result' => $rowData
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('exports.client-companies', $data)->setPaper('a4', 'landscape');

        return $pdf->download($result[0]->company->name.'.pdf');
    }

    /**
     *
     */
    public function viewCompanyPDF( Request $request ){

        // Retrieve request parameters
        $industry_id = $request->industryId;
        $name = $request->companyName;
        $status = $request->status;

        $query = Company::select( 'industry_id', 'name', 'sort_name', 'website_link', 'status' );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $industry_id ){
            $query = $query->where( "industry_id", "like", "%{$industry_id}%" );
        }

        if( $name ){
            $query = $query->where( "name", "like", "%{$name}%" );
        }

        if( $status ){
            $query = $query->where( 'status', $status );
        }

        $result = $query->get();
        $rowData = [];
        if( $result ){
            foreach ($result as $ar) {
                $row = [
                    'name' => $ar->name,
                    'sort_name' => $ar->sort_name,
                    'website_link' => $ar->website_link,
                    'industry_name' => $ar->industry->name,
                    'status' => $ar->status == 0 ? 'De-Active' : 'Active',
                ];

                $rowData[] = $row;
            }
        }

        // Customize data based on parameters
        $data = [
            'title' => 'Company List(s)',
            'result' => $rowData
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('backend.pages.companies.pdf-view', $data)->setPaper('a4', 'landscape');

        return $pdf->download('company.pdf');
    }

    /**
     *
     */
    public function viewDepartmentPDF( Request $request ){

        // Retrieve request parameters
        $industry_id = $request->industryId;
        $name = $request->departmentName;
        $status = $request->status;
        $company_id = $request->companyId;

        $query = Department::select( 'industry_id', 'company_id', 'name', 'status' );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $industry_id ){
            $query = $query->where( "industry_id", $industry_id );
        }

        if( $company_id ){
            $query = $query->where( "company_id", $company_id );
        }

        if( $name ){
            $query = $query->where( "name", "like", "%{$name}%" );
        }

        if( $status ){
            $query = $query->where( 'status', $status );
        }

        $result = $query->get();
        $rowData = [];
        if( $result ){
            foreach ($result as $ar) {
                $row = [
                    'name' => $ar->name,
                    'industry_name' => $ar->industry->name,
                    'company_name' => $ar->company->name,
                    'status' => $ar->status == 0 ? 'De-Active' : 'Active',
                ];

                $rowData[] = $row;
            }
        }

        // Customize data based on parameters
        $data = [
            'title' => 'Department List(s)',
            'result' => $rowData
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('backend.pages.departments.pdf-view', $data)->setPaper('a4', 'landscape');

        return $pdf->download('department.pdf');
    }

    /**
     *
     */
    public function downloadClientMeetingCSV( Request $request ){

        // Retrieve request parameters
        $segment_id = $request->segment_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $query = PersonMeeting::where( 'client_id', '>', 0 );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $segment_id != "" && $segment_id != "null"){
            $query = $query->where( "segment_id", $segment_id );
        }

        if( $start_date != "" && $end_date != "" ){
            $query = $query->whereBetween('follow_up_date', [$start_date, $end_date]);
        } else {
            if( $start_date != "" ){
                $query = $query->where( "follow_up_date", ">=", $start_date );
            }

            if( $end_date != "" ){
                $query = $query->where( 'follow_up_date', "<=", $end_date );
            }
        }

        if( $status != "" ){
            $query = $query->where( 'status', $status );
        }

        $result = $query->get();

        $handle = fopen('php://temp', 'w+');
        $header = ['Unique ID', 'Client Name', 'Meeting Title', 'Time', 'Type', 'Remarks', 'Discussion', 'Status'];
        fputcsv($handle, $header);

        $filename = "data-".time().".csv";

        if( $result ){
            $statusArr = [
                'De-Active',
                'Active',
                'Hold',
                'On Going',
                'Complete'
            ];
            foreach ($result as $ar) {
                $row = [
                    $ar->client->unique_id,
                    $ar->client->first_name.' '.$ar->client->last_name,
                    $ar->title,
                    $ar->follow_up_date,
                    $ar->communication_type->name,
                    $ar->description,
                    $ar->follow_up_detail,
                    $statusArr[$ar->status],
                ];

                fputcsv($handle, $row);
            }
        }

        rewind($handle);
        $csvOutput = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     *
     */
    public function viewClientMeetingPDF( Request $request ){

        // Retrieve request parameters
        $segment_id = $request->segment_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $query = PersonMeeting::where( 'client_id', '>', 0 );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $segment_id != "" && $segment_id != "null"){
            $query = $query->where( "segment_id", $segment_id );
        }

        if( $start_date != "" && $end_date != "" ){
            $query = $query->whereBetween('follow_up_date', [$start_date, $end_date]);
        } else {
            if( $start_date != "" ){
                $query = $query->where( "follow_up_date", ">=", $start_date );
            }

            if( $end_date != "" ){
                $query = $query->where( 'follow_up_date', "<=", $end_date );
            }
        }

        if( $status != "" ){
            $query = $query->where( 'status', $status );
        }

        // startQueryLog();
        $result = $query->get();
        // displayQueryResult();die;

        $rowData = [];
        if( $result ){

            $statusArr = [
                'De-Active',
                'Active',
                'Hold',
                'On Going',
                'Complete'
            ];

            foreach ($result as $ar) {
                $row = [
                    'unique_id' => $ar->client->unique_id,
                    'client_name' => $ar->client->first_name.' '.$ar->client->last_name,
                    'meeting_title' => $ar->title,
                    'date_time' => $ar->follow_up_date,
                    'type' => $ar->communication_type->name,
                    'remarks' => $ar->description,
                    'discussion' => $ar->follow_up_detail,
                    'status' => $statusArr[$ar->status],
                ];

                $rowData[] = $row;
            }
        }

        // Customize data based on parameters
        $data = [
            'title' => 'Client Meeting List(s)',
            'result' => $rowData
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('backend.pages.client-meeting.pdf-view', $data)->setPaper('a4', 'landscape');

        return $pdf->download('client-meeting.pdf');
    }

    /**
     *
     */
    public function downloadCompanyMeetingCSV( Request $request ){

        // Retrieve request parameters
        $segment_id = $request->segment_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $query = CompanyMeeting::where( 'company_id', '>', 0 );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $segment_id != "" && $segment_id != "null"){
            $query = $query->where( "segment_id", $segment_id );
        }

        if( $start_date != "" && $end_date != "" ){
            $query = $query->whereBetween('follow_up_date', [$start_date, $end_date]);
        } else {
            if( $start_date != "" ){
                $query = $query->where( "follow_up_date", ">=", $start_date );
            }

            if( $end_date != "" ){
                $query = $query->where( 'follow_up_date', "<=", $end_date );
            }
        }

        if( $status != "" ){
            $query = $query->where( 'status', $status );
        }

        $result = $query->get();

        $handle = fopen('php://temp', 'w+');
        $header = [ 'Company Name', 'Meeting Title', 'Meeting date', 'Meeting Type', 'Short Description', 'Description', 'Discussion', 'Status'];
        fputcsv($handle, $header);

        $filename = "data-".time().".csv";

        if( $result ){
            $statusArr = [
                'De-Active',
                'Active',
                'Hold',
                'On Going',
                'Complete'
            ];
            foreach ($result as $ar) {
                $row = [
                    $ar->company->name,
                    $ar->title,
                    $ar->date,
                    $ar->communication_type->name,
                    $ar->short_description,
                    $ar->description,
                    $ar->follow_up_detail,
                    $statusArr[$ar->status],
                ];

                fputcsv($handle, $row);
            }
        }

        rewind($handle);
        $csvOutput = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     *
     */
    public function viewCompanyMeetingPDF( Request $request ){

        // Retrieve request parameters
        $segment_id = $request->segment_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $query = CompanyMeeting::where( 'company_id', '>', 0 );

        if( !$this->is_assign_super_admin ){
            $query = $query->where( 'admin_id', $this->admin_id );
        }

        if( $segment_id != "" && $segment_id != "null"){
            $query = $query->where( "segment_id", $segment_id );
        }

        if( $start_date != "" && $end_date != "" ){
            $query = $query->whereBetween('follow_up_date', [$start_date, $end_date]);
        } else {
            if( $start_date != "" ){
                $query = $query->where( "follow_up_date", ">=", $start_date );
            }

            if( $end_date != "" ){
                $query = $query->where( 'follow_up_date', "<=", $end_date );
            }
        }

        if( $status != "" ){
            $query = $query->where( 'status', $status );
        }

        // startQueryLog();
        $result = $query->get();
        // displayQueryResult();die;

        $rowData = [];
        if( $result ){

            $statusArr = [
                'De-Active',
                'Active',
                'Hold',
                'On Going',
                'Complete'
            ];

            foreach ($result as $ar) {
                $row = [
                    'company_name' => $ar->company->name,
                    'meeting_title' => $ar->title,
                    'date_time' => $ar->follow_up_date,
                    'type' => $ar->communication_type->name,
                    'short_description' => $ar->short_description,
                    'description' => $ar->description,
                    'discussion' => $ar->follow_up_detail,
                    'status' => $statusArr[$ar->status],
                ];

                $rowData[] = $row;
            }
        }

        // Customize data based on parameters
        $data = [
            'title' => 'Client Meeting List(s)',
            'result' => $rowData
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('backend.pages.companies.meeting-pdf-view', $data)->setPaper('a4', 'landscape');

        return $pdf->download('company-meeting.pdf');
    }

    /**
     *
     */
    public function downloadAccountSummeryCSVFile( Request $request ){

        $result = $this->accountSummeryDataCalculation( $request, $request->paymentType ?? [] );
        return Excel::download(new TransactionExport( $result['data'], $result['companyObj'], $result['companyBankAccountObj'], $result['cashAvailable'] ), 'transactions.xlsx');
    }

    /**
     *
     */
    public function downloadAccountSummeryPDFFile( Request $request ){

        $paymentType = explode( ",", ltrim( $request->paymentType, "," ) );
        $result = $this->accountSummeryDataCalculation( $request, $paymentType );
        // Generate the PDF
        $pdf = Pdf::loadView('backend.pages.accounting.transactions', $result)->setPaper('a3', 'landscape');

        return $pdf->download($result['companyObj']['name'].'.pdf');
    }

    /**
     *
     */
    public function viewAccountSummery( Request $request ){
        $paymentType = explode( ",", ltrim( $request->paymentType, "," ) );
        $result = $this->accountSummeryDataCalculation( $request, $paymentType );
        return view('backend.pages.accounting.transactions', $result);
    }

    /**
     *
     */
    public function _downloadAccountSummeryCSVFile( Request $request ){

        // Retrieve request parameters
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $company_id = $request->companyId;
        $paymentType = $request->paymentType ?? [];
        $cashAvailable = false;

        $query = Accounting::select( 'id', 'admin_id', 'company_id', 'txn_no', 'debit_amount', 'credit_amount', 'balance', 'description', 'company_code', 'payment_type', 'date', 'updated_at' );

        if( $from_date && $to_date ){
            $query = $query->whereBetween( "date", [$from_date, $to_date] );
        }

        if( $company_id ){
            $query = $query->where( 'company_id', $company_id );
        }

        if( COUNT( $paymentType ) ){
            $query = $query->whereIn( 'payment_type', $paymentType );
        }

        $result = $query->get();

        /**
         * Set Default bank balance
         */

            $companyBankAccountQuery = BankInformation::where([ 'company_id' => $company_id ]);

            if( COUNT( $paymentType ) ){
                $companyBankAccountQuery = $companyBankAccountQuery->whereIn( 'id', $paymentType );
            }

            $companyBankAccountObj = $companyBankAccountQuery->pluck('slug')->toArray();

            if( COUNT( $companyBankAccountObj ) > 0 ){
                $balanceArr['cash_balance'] = 0;
                foreach( $companyBankAccountObj as $slug ){
                    $balanceArr[$slug.'_balance'] = 0;
                }
            } else {
                $balanceArr['cash_balance'] = 0;
            }

        /**
         * generate exccel sheet column data
         */
            $dateObj = Carbon::parse($from_date)->subDay();
            $defaultDate = $dateObj->toDateString();
            $excelDataArr = [];

            foreach( $result as $k=>$data ){

                /**
                 * Default set credit, debit, balance amount
                 */
                    if( in_array( 0, $paymentType ) || COUNT( $paymentType ) == 0 ){
                        $excelDataArr[$k]['cash_credit'] = '0.00';
                        $excelDataArr[$k]['cash_debit'] = '0.00';
                        $excelDataArr[$k]['cash_balance'] = $balanceArr['cash_balance'];
                        $cashAvailable = true;
                    }

                    if( count( $data->company->bank ) ){
                        foreach( $data->company->bank as $bank ){
                            if( in_array( $bank->slug, $companyBankAccountObj ) ){
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_credit'] = '0.00';
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_debit'] = '0.00';
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_balance'] = $balanceArr[$bank->slug.'_balance'];
                            }
                        }
                    }

                /**
                 * Set Dynamic column data
                 */
                    $excelDataArr[$k]['serial_number'] = $data->txn_no;
                    $excelDataArr[$k]['date'] = $data->date;
                    $excelDataArr[$k]['user_name'] = $data->admin->username;
                    $excelDataArr[$k]['payment_type'] = ( $data->payment_type == 0 ) ? 'Cash' : $data->payment->bank_name;
                    $excelDataArr[$k]['company_code'] = $data->client_company->code;
                    $excelDataArr[$k]['company_name'] = $data->client_company->name;
                    $excelDataArr[$k]['description'] = strip_tags( $data->description );

                    /**
                     * Set Dynamic credit, debit, balance amount column data
                     */
                    if( $data->payment_type == 0 ){

                        if( $balanceArr['cash_balance'] == 0 ){
                            $accountBalanceObj = Accounting::where( [
                                'date' => $defaultDate,
                                'company_id' => $company_id
                            ] )
                            ->select('balance')
                            ->orderBy('id', 'desc')
                            ->first();

                            if( $accountBalanceObj ){
                                $balanceArr['cash_balance'] = $accountBalanceObj->balance;
                            } else {
                                if( $data->credit_amount > 0 ){
                                    $balanceArr['cash_balance'] += $data->credit_amount;
                                } else if( $data->debit_amount > 0 ){
                                    $balanceArr['cash_balance'] -= $data->debit_amount;
                                }
                            }
                        } else {
                            if( $data->credit_amount > 0 ){
                                $balanceArr['cash_balance'] += $data->credit_amount;
                            } else if( $data->debit_amount > 0 ){
                                $balanceArr['cash_balance'] -= $data->debit_amount;
                            }
                        }

                        $excelDataArr[$k]['cash_credit'] = $data->credit_amount;
                        $excelDataArr[$k]['cash_debit'] = $data->debit_amount;
                        $excelDataArr[$k]['cash_balance'] = $balanceArr['cash_balance'];
                    } else {
                        if( count( $data->company->bank ) ){
                            foreach( $data->company->bank as $bank ){
                                if( $data->payment_type == $bank->id ){

                                    if( $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] == 0 ){
                                        $accountBalanceObj = Accounting::where( [
                                            'date' => $defaultDate,
                                            'company_id' => $company_id,
                                            'payment_type'  => $data->payment_type
                                        ] )
                                        ->select('balance')
                                        ->orderBy('id', 'desc')
                                        ->first();

                                        if( $accountBalanceObj ){
                                            $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] = $accountBalanceObj->balance;
                                        } else {
                                            if( $data->credit_amount > 0 ){
                                                $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] += $data->credit_amount;
                                            } else if( $data->debit_amount > 0 ){
                                                $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] -= $data->debit_amount;
                                            }
                                        }
                                    } else {
                                        if( $data->credit_amount > 0 ){
                                            $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] -= $data->credit_amount;
                                        } else if( $data->debit_amount > 0 ){
                                            $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] += $data->debit_amount;
                                        }
                                    }

                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_credit'] = $data->credit_amount;
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_debit'] = $data->debit_amount;
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_balance'] = $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'];
                                }
                            }
                        }
                    }

            }

        /*
         * get company details
         */
            $companyObj = Company::select( 'balance', 'total_credit', 'total_debit' )->find( $company_id );

        return Excel::download(new TransactionExport( $excelDataArr, $companyObj, $companyBankAccountObj, $cashAvailable ), 'transactions.xlsx');
    }

    /**
     *
     */
    public function _accountSummeryDataCalculation($request, $paymentType = [])
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $company_id = $request->companyId;

        $cashAvailable = false;

        // Clean and validate paymentType
        $paymentType = array_filter($paymentType ?? [], fn($val) => !is_null($val));

        // Base query with eager loading to avoid N+1 issue
        $query = Accounting::select(
                'id', 'admin_id', 'company_id', 'txn_no',
                'debit_amount', 'credit_amount', 'balance',
                'description', 'remarks', 'company_code',
                'payment_type', 'date', 'updated_at'
            )
            ->with([
                'admin:id,username',
                'client_company:id,code,name',
                'company.bank:id,company_id,slug,bank_name',
                'payment:id,bank_name'
            ])
            ->when($from_date && $to_date, fn($q) => $q->whereBetween('date', [$from_date, $to_date]))
            ->when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->when(!empty($paymentType), fn($q) => $q->whereIn('payment_type', $paymentType));

        $result = $query->get();

        // Fetch company bank slugs
        $companyBankAccountQuery = BankInformation::where('company_id', $company_id);
        if (!empty($paymentType)) {
            $companyBankAccountQuery->whereIn('id', $paymentType);
        }
        $companyBankAccountObj = $companyBankAccountQuery->pluck('slug', 'id')->toArray();

        // Get cash balance
        $baseAccountingQuery = Accounting::where('company_id', $company_id)
            ->whereDate('date', '<', $from_date);

        $accountCashBalanceObj = (clone $baseAccountingQuery)
            ->where('payment_type', 0)
            ->select('balance')
            ->orderByDesc('id')
            ->first();

        $balanceArr['cash_balance'] = $accountCashBalanceObj->balance ?? '0.00';

        // Get each bank balance
        foreach ($companyBankAccountObj as $id => $slug) {
            $accountBalanceObj = (clone $baseAccountingQuery)
                ->where('payment_type', $id)
                ->select('balance')
                ->orderByDesc('id')
                ->first();

            $balanceArr[$slug . '_balance'] = $accountBalanceObj->balance ?? '0.00';
        }

        // Prepare Excel data
        $excelDataArr = [];
        foreach ($result as $k => $data) {
            $row = [];

            if (in_array(0, $paymentType)) {
                $row['cash_credit'] = '0.00';
                $row['cash_debit'] = '0.00';
                $row['cash_balance'] = $balanceArr['cash_balance'];
                $cashAvailable = true;
            }

            if (!empty($data->company->bank)) {
                foreach ($data->company->bank as $bank) {
                    $slug = $bank->slug;
                    if (isset($companyBankAccountObj[$bank->id])) {
                        $row['companyBankAccountObj'][$slug . '_credit'] = '0.00';
                        $row['companyBankAccountObj'][$slug . '_debit'] = '0.00';
                        $row['companyBankAccountObj'][$slug . '_balance'] = $balanceArr[$slug . '_balance'] ?? '0.00';
                    }
                }
            }

            $row['serial_number'] = $data->txn_no;
            $row['date'] = $data->date;
            $row['user_name'] = $data->admin->username ?? '';
            $row['payment_type'] = ($data->payment_type == 0) ? 'Cash' : ($data->payment->bank_name ?? '');
            $row['company_code'] = $data->client_company->code ?? '';
            $row['company_name'] = $data->client_company->name ?? '';
            $row['description'] = strip_tags($data->description);
            $row['remarks'] = strip_tags($data->remarks);

            // Credit/Debit Logic
            if ($data->payment_type == 0) {
                $balanceArr['cash_balance'] = $data->balance;
                $row['cash_credit'] = $data->credit_amount;
                $row['cash_debit'] = $data->debit_amount;
                $row['cash_balance'] = $data->balance;
            } else {
                foreach ($data->company->bank as $bank) {
                    if ($data->payment_type == $bank->id) {
                        $slug = convertStringToSlug($bank->bank_name);
                        $balanceArr[$slug . '_balance'] = $data->balance;
                        $row['companyBankAccountObj'][$slug . '_credit'] = $data->credit_amount;
                        $row['companyBankAccountObj'][$slug . '_debit'] = $data->debit_amount;
                        $row['companyBankAccountObj'][$slug . '_balance'] = $data->balance;
                    } elseif (in_array($bank->id, $paymentType)) {
                        $slug = convertStringToSlug($bank->bank_name);
                        $row['companyBankAccountObj'][$slug . '_balance'] = $balanceArr[$slug . '_balance'] ?? '0.00';
                    }
                }
            }

            $excelDataArr[$k] = $row;
        }

        // Get company summary
        $companyObj = Company::select('name', 'balance', 'total_credit', 'total_debit')->find($company_id);

        return [
            'data' => $excelDataArr,
            'cashAvailable' => $cashAvailable,
            'companyBankAccountObj' => $companyBankAccountObj,
            'companyObj' => $companyObj
        ];
    }

    public function accountSummeryDataCalculation( $request, $paymentType = [] ){

        // Retrieve request parameters
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $company_id = $request->companyId;

        $cashAvailable = false;

        $query = Accounting::select( 'id', 'admin_id', 'company_id', 'txn_no', 'debit_amount', 'credit_amount', 'balance', 'description', 'remarks', 'company_code', 'payment_type', 'date', 'updated_at' );

        if( $from_date && $to_date ){
            $query->whereBetween( "date", [$from_date, $to_date] );
        }

        if( $company_id ){
            $query->where( 'company_id', $company_id );
        }

        $paymentType = array_filter($paymentType, fn($val) => !is_null($val));
        if( COUNT( $paymentType ) >0 ){
            $query->whereIn( 'payment_type', $paymentType );
        }

        $query->orderBy('date')
            ->orderBy('id')
            // orderBy( 'id', 'DESC' )
        ;

        $result = $query->get();

        /**
         * Set Default bank balance
         */

            $companyBankAccountQuery = BankInformation::where([ 'company_id' => $company_id ]);

            if( COUNT( $paymentType ) >0 ){
                $companyBankAccountQuery = $companyBankAccountQuery->whereIn( 'id', $paymentType );
            }

            $companyBankAccountObj = $companyBankAccountQuery->pluck('slug', 'id')->toArray();

            $accountCashBalanceObj = Accounting::whereDate('date', '<', $from_date )//Carbon::createFromFormat('Y-m-d',
                ->where( [
                    'company_id' => $company_id,
                    'payment_type'  =>0
                ] )
                ->select('balance')
                ->orderBy('id', 'desc')
                ->first();

                if( $accountCashBalanceObj ){
                    $balanceArr['cash_balance'] = $accountCashBalanceObj->balance;
                } else {
                    $balanceArr['cash_balance'] = '0.00';
                }

            if( COUNT( $companyBankAccountObj ) > 0 ){
                foreach( $companyBankAccountObj as $id=>$slug ){

                    $accountBalanceObj = Accounting::whereDate('date', '<', $from_date )//Carbon::createFromFormat('Y-m-d',
                        ->where( [
                            'company_id' => $company_id,
                            'payment_type'  => $id
                        ] )
                        ->select('balance')
                        ->orderBy('id', 'desc')
                        ->first();

                        if( $accountBalanceObj ){
                            $balanceArr[$slug.'_balance'] = $accountBalanceObj->balance;
                        } else {
                            $balanceArr[$slug.'_balance'] = '0.00';
                        }
                }
            }

        /**
         * generate exccel sheet column data
         */
            $dateObj = Carbon::parse($from_date)->subDay();
            $defaultDate = $dateObj->toDateString();
            $excelDataArr = [];

            foreach( $result as $k=>$data ){

                /**
                 * Set Default credit, debit, balance amount
                 */
                    if( in_array( 0, $paymentType ) ){// || COUNT( $paymentType ) == 0
                        $excelDataArr[$k]['cash_credit'] = '0.00';
                        $excelDataArr[$k]['cash_debit'] = '0.00';
                        $excelDataArr[$k]['cash_balance'] = $balanceArr['cash_balance'];
                        $cashAvailable = true;
                    }

                    if( count( $data->company->bank ) ){
                        foreach( $data->company->bank as $bank ){
                            if( in_array( $bank->slug, $companyBankAccountObj ) ){
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_credit'] = '0.00';
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_debit'] = '0.00';
                                $excelDataArr[$k]['companyBankAccountObj'][$bank->slug.'_balance'] = $balanceArr[$bank->slug.'_balance'];
                            }
                        }
                    }

                /**
                 * Set Dynamic column data
                 */
                    $excelDataArr[$k]['serial_number'] = $data->txn_no;
                    $excelDataArr[$k]['date'] = $data->date;
                    $excelDataArr[$k]['user_name'] = $data->admin->username;
                    $excelDataArr[$k]['payment_type'] = ( $data->payment_type == 0 ) ? 'Cash' : $data->payment->bank_name;
                    $excelDataArr[$k]['company_code'] = $data->client_company->code ?? '';
                    $excelDataArr[$k]['company_name'] = $data->client_company->name ?? '';
                    $excelDataArr[$k]['description'] = strip_tags( $data->description );
                    $excelDataArr[$k]['remarks'] = strip_tags( $data->remarks );

                    /**
                     * Set Dynamic credit, debit, balance amount column data
                     */
                    if( $data->payment_type == 0 ){

                        $balanceArr['cash_balance'] = $data->balance;
                        $excelDataArr[$k]['cash_credit'] = $data->credit_amount;
                        $excelDataArr[$k]['cash_debit'] = $data->debit_amount;
                        $excelDataArr[$k]['cash_balance'] = $data->balance;
                    } else {
                        if( count( $data->company->bank ) ){
                            foreach( $data->company->bank as $bank ){
                                if( $data->payment_type == $bank->id ){

                                    $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'] = $data->balance;
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_credit'] = $data->credit_amount;
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_debit'] = $data->debit_amount;
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_balance'] = $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'];
                                } else if( in_array( $bank->id, $paymentType ) ){
                                    $excelDataArr[$k]['companyBankAccountObj'][convertStringToSlug( $bank->bank_name ).'_balance'] = $balanceArr[convertStringToSlug( $bank->bank_name ).'_balance'];
                                }
                            }
                        }
                    }

            }

        /*
         * get company details
         */
        $companyObj = Company::select( 'name', 'balance', 'total_credit', 'total_debit' )->find( $company_id );
        return [ 'data' => $excelDataArr, 'cashAvailable' => $cashAvailable, 'companyBankAccountObj' => $companyBankAccountObj, 'companyObj' => $companyObj ];
    }

    /**
     *
     */
    public function uploadAccountSummeryCSVFile( Request $request ){

        Excel::import( new AccountSummaryImport( $request->company_id ), $request->file('excel_file') );

        //To recursively fetch and update one record at a time in Laravel until no more records are left, you can use a while loop.
        updateAccountSummeryBalance( $request->company_id );

        return back()->with('success', 'Excel file imported successfully');
    }
}
