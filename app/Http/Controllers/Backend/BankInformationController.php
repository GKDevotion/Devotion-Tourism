<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\BankInformation;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BankInformationController extends Controller
{
    public $user;

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
    public function index( Request $request, $company_id )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'bank-information', 'view')) {
            $logArr['description'] = "Unauthorized try to load bank information data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view Accounting data !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );

        $logArr['description'] = "Bank information loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.bank-information.index', compact( 'auth', 'company' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $edit = fetchSinglePermission( $this->user, 'bank-information', 'edit');
        $delete = fetchSinglePermission( $this->user, 'bank-information', 'delete');

        $query = BankInformation::query();

        if( $request->cid ){
            $query->where( 'company_id', $request->cid );
        }

        $query->select('id', 'company_id', 'currency_id', 'bank_name', 'holder_name', 'account_number', 'ifsc_code', 'branch_code', 'iban', 'status', 'updated_at');

        return DataTables::eloquent($query)
            ->addColumn('id', function(BankInformation $ar) {
                return $ar->id;
            })
            ->addColumn('currency', function(BankInformation $ar) {
                return $ar->currency->code;
            })
            ->addColumn('bank_name', function(BankInformation $ar) {
                return $ar->bank_name;
            })
            ->addColumn('holder_name', function(BankInformation $ar) {
                return $ar->holder_name;
            })
            ->addColumn('account_number', function(BankInformation $ar) {
                return $ar->account_number;
            })
            ->addColumn('ifsc_code', function(BankInformation $ar) {
                return $ar->ifsc_code;
            })
            ->addColumn('branch_code', function(BankInformation $ar) {
                return $ar->branch_code;
            })
            ->addColumn('iban', function(BankInformation $ar) {
                return $ar->iban;
            })
            ->addColumn('status', function(BankInformation $ar) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $ar->status == 0 ? 'times' : 'check').' update-status" data-status="'.$ar->status.'" data-id="'.$ar->id.'" aria-hidden="true" data-table="bank_informations"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $ar->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$ar->id.'" data-table="bank_informations">
                            <option value="1" '.($ar->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($ar->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('updated_at', function(BankInformation $ar) {
                return formatDate( "Y-m-d H:i", $ar->updated_at );
            })
            ->addColumn('action', function(BankInformation $ar ) use ( $edit, $delete ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$ar->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$ar->id.'">
                    ';

                    if ( $edit && auth()->guard('admin')->user()->admin_user_group_id == 1 ) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('company-bank-information-edit', $ar->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($delete) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$ar->id.'" data-title="'.$ar->bank_name.'" data-segment="bank-information">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'bank_name', 'holder_name', 'account_number', 'ifsc_code', 'branch_code', 'iban', 'status', 'currency', 'updated_at', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    if( $searchValue != "" ){
                        $query->where('name', 'like', "%{$searchValue}%")
                            ->orWhere('type', 'like', "%{$searchValue}%");
                        }
                }
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request('order')[0]['column'];
                    $orderDirection = request('order')[0]['dir'];
                    $columns = request('columns');
                    $query->orderBy($columns[$orderColumn]['data'], $orderDirection);
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request, $company_id = null)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "C";

        if (!fetchSinglePermission( $this->user, 'bank-information', 'add')) {
            $logArr['description'] = "Unauthorized try to create bank details";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Account Field !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );
        $currency = Currency::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();

        $logArr['description'] = "Bank form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.bank-information.create', compact('auth', 'company', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "S";

        if (!fetchSinglePermission( $this->user, 'bank-information', 'add')) {
            $logArr['description'] = "Unauthorized try to store bank data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Field !');
        }

        // Validation Data
        $request->validate([
            'bank_name' => 'required',
            'holder_name' => 'required',
            'account_number' => 'required',
            'branch_code' => 'required',
            'currency_id' => 'required'
        ]);

        // Create New Server Record
        $dataObj = new BankInformation();
        $dataObj->admin_id = $this->user->id;
        $dataObj->company_id = $request->company_id;
        $dataObj->currency_id = $request->currency_id;
        $dataObj->bank_name = $request->bank_name;
        $dataObj->slug = convertStringToSlug( $request->bank_name );
        $dataObj->holder_name = $request->holder_name;
        $dataObj->account_number = $request->account_number;
        $dataObj->ifsc_code = $request->ifsc_code;
        $dataObj->iban = $request->iban;
        $dataObj->branch_code = $request->branch_code;
        $dataObj->description = $request->description;
        $dataObj->status = 1;
        $dataObj->save();

        $logArr['description'] = $request->account_number.' bank record has been created !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('company-bank-information-index', $request->company_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Request $request, int $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "E";

        if (!fetchSinglePermission( $this->user, 'bank-information', 'edit') ) {
            $logArr['description'] = "Unauthorized try to edit bank account";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Field !');
        }

        $data = BankInformation::find($id);
        $auth = $this->user;
        $currency = Currency::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();

        $logArr['description'] = "Bank form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.bank-information.edit', compact('data', 'auth', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (!fetchSinglePermission( $this->user, 'bank-information', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update bank account";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Field !');
        }

        // Validation Data
        $request->validate([
            'bank_name' => 'required',
            'holder_name' => 'required',
            'account_number' => 'required',
            'branch_code' => 'required',
            'currency_id' => 'required'
        ]);

        // Create New Server Record
        $dataObj = BankInformation::find( $id );
        $dataObj->admin_id = $this->user->id;
        $dataObj->company_id = $request->company_id;
        $dataObj->currency_id = $request->currency_id;
        $dataObj->bank_name = $request->bank_name;
        $dataObj->slug = convertStringToSlug( $request->bank_name );
        $dataObj->holder_name = $request->holder_name;
        $dataObj->account_number = $request->account_number;
        $dataObj->ifsc_code = $request->ifsc_code;
        $dataObj->iban = $request->iban;
        $dataObj->branch_code = $request->branch_code;
        $dataObj->description = $request->description;
        $dataObj->save();

        $logArr['description'] = $request->account_number.' bank record has been updated !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('company-bank-information-index', $request->company_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, int $id)
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "D";

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'bank-information', 'delete')) {
            $logArr['description'] = "Unauthorized try to delete bank account data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete Field !');
        }

        $dataObj = BankInformation::find($id);

        if (!is_null($dataObj)) {

            //check account summery data available
            $checkEntry = Accounting::where( 'payment_type', $dataObj->id )->first();

            if( $checkEntry ){

                $logArr['description'] = "'".$dataObj->bank_name.'" bank summery data available, so delete first and then try again.';
                saveAdminLog( $logArr );// Save Access log history

                return response()->json( ['data' => ['message' => $logArr['description'], 'status' => 201 ] ], 200);
            } else {
                $dataObj->delete();
            }
        }

        $logArr['description'] = "'".$dataObj->name.'" bank has been successfully deleted.';
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => $logArr['description'], 'status' => 200 ] ], 200);
    }
}
