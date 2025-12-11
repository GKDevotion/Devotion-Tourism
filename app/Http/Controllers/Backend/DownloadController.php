<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\TransactionExport;
use App\Imports\ClientCompanyImport;
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
    public function uploadClientCompanyCSVFile( Request $request ){

        Excel::import( new ClientCompanyImport( $request->company_id ), $request->file('excel_file') );

        return back()->with('success', 'Excel file imported successfully');
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

}
