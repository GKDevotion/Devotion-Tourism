<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\AccountManagememt;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AccountManagementController extends Controller
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
    public function index( Request $request, $company_id=null )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'account-management', 'view')) {
            $logArr['description'] = "Unauthorized try to view account management data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view Accounting data !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );

        $logArr['description'] = "Load account management data successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-management.index', compact( 'auth', 'company' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $edit = fetchSinglePermission( $this->user, 'account-management', 'edit');
        $delete = fetchSinglePermission( $this->user, 'account-management', 'delete');

        $query = AccountManagememt::query();

        $query->select('id', 'name', 'code', 'company_id', 'status', 'created_at')->where( 'company_id', $request->cid );

        return DataTables::eloquent($query)
            ->addColumn('id', function(AccountManagememt $ar) {
                return $ar->id;
            })
            ->addColumn('name', function(AccountManagememt $ar) {
                return $ar->name;
            })
            ->addColumn('company', function(AccountManagememt $ar) {
                return $ar->company->name;
            })
            ->addColumn('code', function(AccountManagememt $ar) {
                return $ar->code;
            })
            ->addColumn('status', function(AccountManagememt $ar) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $ar->status == 0 ? 'times' : 'check').' update-status" data-status="'.$ar->status.'" data-id="'.$ar->id.'" aria-hidden="true" data-table="account_managements"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $ar->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$ar->id.'" data-table="account_managements">
                            <option value="1" '.($ar->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($ar->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('created_at', function(AccountManagememt $ar) {
                return formatDate( "Y-m-d H:i", $ar->created_at );
            })
            ->addColumn('action', function(AccountManagememt $ar ) use ( $edit, $delete ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$ar->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$ar->id.'">
                    ';

                    if ( $edit) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('company-account-management-edit', $ar->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($delete) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$ar->id.'" data-title="'.$ar->name.'" data-segment="account-managements">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'name', 'company', 'code', 'updated_at', 'status', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    if( $searchValue != "" ){
                        $query->where(function ($query) use ($searchValue) {
                            $query->where('name', 'like', "%{$searchValue}%")
                                ->orWhere('code', 'like', "%{$searchValue}%");
                        });
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

        if (!fetchSinglePermission( $this->user, 'account-management', 'add')) {
            $logArr['description'] = "Unauthorized try to create company account management";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Account Field !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );

        $logArr['description'] = "Load account management form successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-management.create', compact('auth', 'company'));
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

        if (!fetchSinglePermission( $this->user, 'account-management', 'add')) {
            $logArr['description'] = "Unauthorized try to store company account management data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create account management data !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'code' => [
                    'required',
                    Rule::unique('account_managements')->where(function ($query) use ($request) {
                        return $query->where([
                            'code' => $request->code,
                            'company_id' => $request->company_id
                        ]);
                    }),
            ],
            'company_id' => 'required',
            // 'company_id' => 'required|exists:companies,id',
        ]);

        // Create New Server Record
        $dataObj = new AccountManagememt();
        $dataObj->admin_id = $this->user->id;
        $dataObj->company_id = $request->company_id;
        $dataObj->name = $request->name;
        $dataObj->code = ( $request->code != "" ) ? $request->code : appendClientCompanyUniqueId( $request->company_id );//$request->code;
        $dataObj->status = $request->status;
        $dataObj->save();

        $logArr['description'] = $request->name.' record has been created !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('company-account-management-index', $request->company_id);
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

        if (!fetchSinglePermission( $this->user, 'account-management', 'edit') ) {
            $logArr['description'] = "Unauthorized try to edit company account management";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Field !');
        }

        $auth = $this->user;
        $data = AccountManagememt::find($id);

        $logArr['description'] = "Load account management form successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-management.edit', compact('data', 'auth'));
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

        if (!fetchSinglePermission( $this->user, 'account-management', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update company account management data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to update Field data!');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'code' => [
                    'required',
                    Rule::unique('account_managements')->where(function ($query) use ($request, $id) {
                        return $query->where([
                            'code' => $request->code,
                            'company_id' => $request->company_id
                        ])
                        ->where( 'id', '<>', $id );
                    }),
            ],
            'company_id' => 'required',
        ]);

        // Create New Server Record
        $dataObj = AccountManagememt::find( $id );

        $oldDataObj = clone $dataObj; // or use: $dataObj->replicate();

        $dataObj->admin_id = $this->user->id;
        $dataObj->company_id = $request->company_id;
        $dataObj->name = $request->name;
        $dataObj->code = $request->code;
        $dataObj->status = $request->status;
        $dataObj->save();

        $logArr['description'] = $request->name.' record has been updated !!';

        $logArr['table_view'] = "<table class='table'>
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>User</td>
                    <td>".$oldDataObj->admin->username."</td>
                    <td>".$dataObj->admin->username."</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>".$oldDataObj->name."</td>
                    <td>".$dataObj->name."</td>
                </tr>
                <tr>
                    <td>Code</td>
                    <td>".$oldDataObj->code."</td>
                    <td>".$dataObj->code."</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>".$oldDataObj->updated_at."</td>
                    <td>".$dataObj->updated_at."</td>
                </tr>
            ";

        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('company-account-management-index', $request->company_id);
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

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'account-management', 'delete')) {
            $logArr['description'] = "Unauthorized try to delete company account management data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete Field !');
        }

        $dataObj = AccountManagememt::find($id);

        if (!is_null($dataObj)) {

            //account_summeries
            $accountMGT = Accounting::select('id')->where('company_code', $dataObj->id)->first();

            if( $accountMGT ){
                $logArr['description'] = "Company account summery data (".$dataObj->name.") already exist.";
                saveAdminLog( $logArr );// Save Access log history

                return response()->json( ['data' => ['message' => $logArr['description'], 'status' => 201] ], 200);
            }

            $dataObj->delete();
        }

        $logArr['description'] = "'".$dataObj->name.'" has been successfully deleted.';
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => $logArr['description'], 'status' => 200 ] ], 200);
    }
}
