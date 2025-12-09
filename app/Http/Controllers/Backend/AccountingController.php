<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\AccountSummeryFileMaps;
use App\Models\BankInformation;
use App\Models\Company;
use App\Models\CompanyAccountFieldIndexing;
use App\Models\CompanyAccountFieldMapping;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AccountingController extends Controller
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
    public function index( Request $request, $company_id = null, $currentPage = 0 )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if( !$company_id ){
            return redirect('admin/companies');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'accounting', 'view')) {

            $logArr['description'] = "Unauthorized try to view account summery";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view Accounting data !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );

        $accountManagementFieldObj = CompanyAccountFieldMapping::join('account_management_fields_indexing', 'account_management_fields_indexing.account_mgt_field_id', '=', 'company_account_mgt_field_maps.account_mgt_field_id')
        ->join('account_management_fields', 'account_management_fields.id', '=', 'company_account_mgt_field_maps.account_mgt_field_id')
        ->select( 'account_management_fields.*' )
        ->where([
            'company_account_mgt_field_maps.company_id' => $company_id,
            'company_account_mgt_field_maps.status' => 1,
            'account_management_fields_indexing.status' => 1,
            'account_management_fields_indexing.company_id' => $company_id,
            'account_management_fields_indexing.is_hidden' => 0,
        ])
        ->orderBy( 'account_management_fields_indexing.sort_order', 'ASC' )
        ->get();

        $paymentType = $request->payment_type ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;

        $bankInfoObj = BankInformation::select('id', 'bank_name')->where( 'company_id', $company_id )->get();

        $logArr['description'] = "Account summery history loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.accounting.index', compact( 'auth', 'company_id', 'company', 'accountManagementFieldObj', 'paymentType', 'bankInfoObj', 'currentPage', 'from_date', 'to_date' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $currentPage = ( $request->start / $request->length ) + 1;
        $auth = $this->user;
        $edit = fetchSinglePermission( $auth, 'accounting', 'edit');
        $delete = fetchSinglePermission( $auth, 'accounting', 'delete');
        $systemDate = getField( "configurations", "key", "value", "CURRENT_DAY" );

        $query = Accounting::query();
        $query->where( 'status', 1 );

        if( $request->cid ){
            $query->where( 'company_id', $request->cid );
        }

        if( request('search')['value'] ){

        } else if( isset( $request->paymentType ) ){
            $paymentTypeArr = explode( ",", $request->paymentType );
            $query->whereIn( 'payment_type', $paymentTypeArr );
        }

        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        if( $from_date && $to_date ){
            $query->whereBetween( "date", [$from_date, $to_date] );
        }

        $query->orderByDesc('date')
            ->orderByDesc('id')
        ;

        $accountManagementFieldObj = CompanyAccountFieldMapping::join('account_management_fields_indexing', 'account_management_fields_indexing.account_mgt_field_id', '=', 'company_account_mgt_field_maps.account_mgt_field_id')
        ->join('account_management_fields', 'account_management_fields.id', '=', 'company_account_mgt_field_maps.account_mgt_field_id')
        ->select( 'account_management_fields.*' )
        ->where([
            'company_account_mgt_field_maps.company_id' => $request->cid,
            'company_account_mgt_field_maps.status' => 1,
            'account_management_fields_indexing.status' => 1,
            'account_management_fields_indexing.company_id' => $request->cid,
            'account_management_fields_indexing.is_hidden' => 0,
        ])
        ->orderBy( 'account_management_fields_indexing.sort_order', 'ASC' )
        ->pluck( 'account_management_fields.slug' )
        ->toArray();


        $rowColumn2 = $accountManagementFieldObj;
        $rowColumn1 = ['action', 'id', 'admin_id', 'company_id', 'txn_no', 'balance', 'updated_at', 'status'];
        $rowColumn = array_merge( $rowColumn1, $rowColumn2 );

        $datatables =  DataTables::of($query)
            ->addColumn('id', function(Accounting $ar) {
                return $ar->id;
            })
            ->addColumn('admin', function(Accounting $ar) {
                return $ar->admin->username;
            })
            ->addColumn('company', function(Accounting $ar) {
                return $ar->company->sort_name;
            })
            ->addColumn('txn_no', function(Accounting $ar) {
                return $ar->txn_no;
            })
            ->addColumn('balance', function(Accounting $ar) {
                return number_format( $ar->balance, 2 );
            })
            ->addColumn('updated_at', function(Accounting $ar) {
                return formatDate( "Y-m-d H:i", $ar->updated_at );
            })
            ->addColumn('action', function(Accounting $ar ) use ( $auth, $edit, $delete, $systemDate, $currentPage ) {

                $res = 0;
                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$ar->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" '.$auth->admin_user_group_id.' '.$auth->id.' '.$ar->admin_id.' '.$systemDate.' aria-labelledby="action_menu_'.$ar->id.'">';

                    if ( $auth->admin_user_group_id == 1 || $auth->admin_user_group_id == 3 ||
                        ( $edit && $systemDate == formatDate( 'd', $ar->created_at ) &&  $auth->id == $ar->admin_id ) )
                    {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route( 'admin.accounting.edit', [ 'accounting' => $ar->id, 'currentPage' => $currentPage ] ).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                        $res = 1;
                    }

                    if ( $delete && ( $auth->admin_user_group_id == 1 || $auth->admin_user_group_id == 3 ) ) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-btn" data-id="'.$ar->id.'" data-title="'.$ar->txn_no.'" data-segment="accounting">
                                    <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                </button>';

                        $res = 1;
                    }

                $action.= '</div>';

                if( $res == 1 ){
                    return $action;
                } else {
                    return '-';
                }
            })
            ->rawColumns( $rowColumn )  // Specify the columns that contain HTML
            ->filter(function ($query) use( $request ) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];

                    if( $searchValue != "" ){

                        $query->where(function ($query) use ($searchValue) {
                            $query->where('txn_no', 'like', "%{$searchValue}%")
                                ->orWhere('balance', 'like', "%{$searchValue}%")
                                ->orWhere('credit_amount', 'like', "%{$searchValue}%")
                                ->orWhere('debit_amount', 'like', "%{$searchValue}%")
                                ->orWhere('description', 'like', "%{$searchValue}%")
                                ->orWhere('remarks', 'like', "%{$searchValue}%")
                                ->orWhere('company_code', 'like', "%{$searchValue}%")
                                ->orWhereHas('client_company', function ($q) use ($searchValue) {
                                    $q->where('code', 'like', "%{$searchValue}%")
                                    ->orWhere('name', 'like', "%{$searchValue}%");
                                })
                                ->orWhereHas('admin', function ($q) use ($searchValue) {
                                    $q->where('username', 'like', "%{$searchValue}%");
                                })
                                ->orWhereHas('payment', function ($q) use ($searchValue) {
                                    $q->where('bank_name', 'like', "%{$searchValue}%");
                                });

                            $dateFormatted = null;

                            // Check if it's a valid d-m-Y date and convert to Y-m-d for DB comparison
                            if (DateTime::createFromFormat('d-m-Y', $searchValue)) {
                                $dateFormatted = Carbon::createFromFormat('d-m-Y', $searchValue)->format('Y-m-d');
                            }

                            // Only apply date filter if user input matches d-m-Y
                            if ($dateFormatted) {
                                $query->orWhere('date', 'like', "%{$dateFormatted}%");
                            }
                        });
                    }
                }
            })
            ->order(function ($query) {
                if ( false && request()->has('order') ) {
                    $orderColumn = request('order')[0]['column'];
                    $orderDirection = request('order')[0]['dir'];
                    $columns = request('columns');
                    $query->orderBy($columns[$orderColumn]['data'], $orderDirection);
                }
            });

        foreach ( $accountManagementFieldObj as $column ) {
            $datatables->addColumn( $column, function ( Accounting $row ) use ( $column, $edit ) {
                $keys = explode('.', $column);
                $value = $row;
                foreach ($keys as $key) {
                    if( $column == "document" ){
                        $value = '-';

                        if( $row->$key ){
                            $value = '';

                            if( $row->upload_file ){
                                $value.= '
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="file_menu_'.$row->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-download"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="file_menu_'.$row->id.'">';

                                    foreach( $row->upload_file as $file ){
                                        $value.= '<a class="btn btn-edit dropdown-item" href="'.url( 'storage/app/'.$file->path ).'" target="_blank">
                                                '.$file->filename.'
                                            </a>';
                                    }

                                $value.= '</div>';

                            } else {
                                $value = '<a class="btn btn-edit" href="'.url( 'storage/app/'.$row->$key ).'" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>';
                            }
                        }
                    } else if( $column == "date" ){
                        $value = formatDate( "d-m-Y", $value->$key );
                    } else if( $column == "payment_type" ){
                        $value = ( $value->payment_type == 0 ) ? 'Cash' : $value->payment->bank_name;
                    } else if( $column == "company_code" ){
                        if( isset( $value->client_company->name ) ){
                            $value = "(".$value->client_company->code.") ".$value->client_company->name;
                        } else {
                            $value = '';
                        }
                    }  else if( $column == "description" ){
                        $value = $value->description;
                    } else if( $column == "crm_update" ){
                        if ( $edit ) {
                            $value = '<select id="crm_update_'.$value->id.'" class="form-control crm-update badge '.( $value->crm_update == 0 ? 'bg-warning text-black' : 'bg-success text-white').'" name="crm_update" data-id="'.$value->id.'" data-table="account_summeries" data-crm="'.$value->crm_update.'">
                                        <option value="1" class="bg-success" '.($value->crm_update == 1 ? 'selected' : '').'>Yes</option>
                                        <option value="0" class="bg-warning" '.($value->crm_update == 0 ? 'selected' : '').'>No</option>
                                    </select>';
                        }
                    } else if( $column == "credit_amount" || $column == "debit_amount" ){
                        $value = number_format( $value->$key, 2 );
                    } else {
                        $value = $value->$key ?? '';
                    }
                }
                return $value;
            });
        }

        return $datatables->make(true);
    }

    protected function renderDynamicColumn($column, $row, $edit)
    {
        switch ($column) {
            case 'document':
                if (!$row->document) return '-';
                if ($row->upload_file && count($row->upload_file)) {
                    $html = '<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="file_menu_'.$row->id.'" data-toggle="dropdown">
                                <i class="fa fa-download"></i>
                            </button><div class="dropdown-menu" aria-labelledby="file_menu_'.$row->id.'">';
                    foreach ($row->upload_file as $file) {
                        $html .= '<a class="btn btn-edit dropdown-item" href="'.url('storage/app/'.$file->path).'" target="_blank">'.$file->filename.'</a>';
                    }
                    return $html . '</div>';
                }
                return '<a class="btn btn-edit" href="'.url('storage/app/'.$row->document).'" target="_blank"><i class="fa fa-download"></i></a>';

            case 'date':
                return formatDate("d-m-Y", $row->date);

            case 'payment_type':
                return $row->payment_type == 0 ? 'Cash' : ($row->payment->bank_name ?? '');

            case 'company_code':
                return $row->client_company ? "({$row->client_company->code}) {$row->client_company->name}" : '';

            case 'description':
                return $row->description;

            case 'crm_update':
                if (!$edit) return $row->crm_update ? 'Yes' : 'No';
                return '<select id="crm_update_'.$row->id.'" class="form-control crm-update badge '.($row->crm_update ? 'bg-success text-white' : 'bg-warning text-black').'" name="crm_update" data-id="'.$row->id.'" data-table="account_summeries" data-crm="'.$row->crm_update.'">
                            <option value="1" class="bg-success" '.($row->crm_update == 1 ? 'selected' : '').'>Yes</option>
                            <option value="0" class="bg-warning" '.($row->crm_update == 0 ? 'selected' : '').'>No</option>
                        </select>';

            case 'credit_amount':
            case 'debit_amount':
                return number_format($row->$column, 2);

            default:
                return $row->$column ?? '';
        }
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

        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "C";

        if (!fetchSinglePermission( $this->user, 'accounting', 'add' ) ) {
            $logArr['description'] = "Unauthorized try to create company account";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Account Summery !');
        }

        $auth = $this->user;
        $company = Company::select( 'id', 'name' )->find( $company_id );
        $accountManagementFieldObj = CompanyAccountFieldIndexing::join('account_management_fields', 'account_management_fields.id', '=', 'account_management_fields_indexing.account_mgt_field_id')
        ->select( 'account_management_fields.*' )
        ->where([
            'account_management_fields_indexing.company_id' => $company_id,
            'account_management_fields_indexing.status' => 1,
            'account_management_fields_indexing.is_hidden' => 0
        ])
        ->orderBy( 'account_management_fields_indexing.sort_order', 'ASC' )
        ->get();

        $logArr['description'] = "Account Summery form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        $today =  getField( "configurations", "key", "value", "CURRENT_DATE" );
        $todayArr = explode("/", $today);
        $todayDate = $todayArr[2]."-".$todayArr[1]."-".$todayArr[0];

        return view('backend.pages.accounting.create', compact('company_id', 'auth', 'company', 'accountManagementFieldObj', 'todayDate'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user)) {
            return redirect('admin/login');
        }

        // process request only once
        // if (session()->pull('form_token') === $request->form_token) {
            $logArr = [
                'admin_id'   => $this->user->id ?? 0,
                'ip_address' => $request->ip(),
                'action'     => 'S',
            ];

            // Permission check
            if (!fetchSinglePermission($this->user, 'accounting', 'add')) {
                $logArr['description'] = "Unauthorized try to store account summery data";
                saveAdminLog($logArr);
                abort(403, 'Sorry !! You are Unauthorized to create Accounting!');
            }

            // Format input array
            $rows = [];
            foreach (array_keys($request->summery) as $key) {
                if( $request->summery[$key] ){
                    foreach ($request->summery[$key] as $i => $value) {
                        $rows[$i][$key] = $value ?? null;
                    }
                }
            }

            // Flush values but keep keys
            array_fill_keys( array_keys( $request->summery ), null );

            $minDate = collect($rows)->min('date'); // returns the oldest date

            foreach ($rows as $i => $data) {

                if ( !empty( $data ) ) {
                    $dataObj = new Accounting([
                        'admin_id'        => $this->user->id,
                        'company_id'      => $request->company_id,
                        'txn_no'          => getAccountSummeryUniqueId($request->company_id),
                        'is_check_balance'=> 0,
                    ]);
                    $dataObj->save();

                    foreach ($data as $key => $val) {
                        if ($key === "document" && !empty($val)) {
                            $folderName = "public/account-summery/{$request->company_id}";
                            Storage::makeDirectory($folderName);
                            @chmod(storage_path("app/{$folderName}"), 0777);

                            foreach ($request->summery['document'][$i] as $f => $file) {
                                $filename = $file->getClientOriginalName();
                                $file->move(storage_path("app/{$folderName}"), $filename);

                                AccountSummeryFileMaps::create([
                                    'account_summery_id' => $dataObj->id,
                                    'company_id'         => $request->company_id,
                                    'txn_no'             => $dataObj->txn_no,
                                    'indexing'           => $f,
                                    'path'               => "{$folderName}/{$filename}",
                                    'filename'           => $filename,
                                    'status'             => 1,
                                ]);
                            }

                            $dataObj->$key = 1;
                        } elseif ($key !== "document") {
                            $dataObj->$key = $val;
                        }
                    }

                    $dataObj->status = 1;
                    $dataObj->save();

                    $rows[$i] = [];

                    $logArr['description'] = "Account <b>{$dataObj->txn_no}</b> Summery data stored successfully";
                    saveAdminLog($logArr);
                }
            }

            // Update balance
            // updateAccountSummeryBalance($request->company_id);
            $this->updateAccountSummeryBalanceData( $minDate, $request->company_id );// find out old date and after all date reset calculation again.

            session()->flash('success', 'Record has been created!!');

            Cache::forget( 'accounting_fields_' . $request->company_id );

        // } else {
        //     session()->flash('error', 'Duplicate submission detected. Please check your previous entry before submitting again.');
        // }

        return redirect()->route('company-account-summery-index', $request->company_id);
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
    public function edit( Request $request, int $id )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "E";

        if (!fetchSinglePermission( $this->user, 'accounting', 'edit' ) ) {
            $logArr['description'] = "Unauthorized try to edit company account summery";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to update Account details !');
        }

        $auth = $this->user;
        $data = Accounting::find($id);

        $company = Company::select( 'id', 'name' )->find( $data->company_id );
        $accountManagementFieldObj = CompanyAccountFieldMapping::join('account_management_fields', 'account_management_fields.id', '=', 'company_account_mgt_field_maps.account_mgt_field_id')
            ->select( 'account_management_fields.*' )
            ->where([
                'company_account_mgt_field_maps.company_id' => $data->company_id,
                'company_account_mgt_field_maps.status' => 1
            ])
            ->orderBy( 'account_management_fields.sort_order', 'ASC' )
            ->get();

        $logArr['description'] = "Summery form loaded successfully";
        saveAdminLog( $logArr );// Save Access log history

        $currentPage = $request->currentPage ?? 0;
        $today =  getField( "configurations", "key", "value", "CURRENT_DATE" );
        $todayArr = explode("/", $today);
        $todayDate = $todayArr[2]."-".$todayArr[1]."-".$todayArr[0];
        return view('backend.pages.accounting.edit', compact('data', 'company', 'auth', 'accountManagementFieldObj', 'currentPage', 'todayDate'));
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

        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (!fetchSinglePermission( $this->user, 'accounting', 'edit' )) {
            $logArr['description'] = "Unauthorized try to update account summery data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Account Summery !');
        }

        $dataObj = Accounting::find( $id );

        $oldDataObj = clone $dataObj; // or use: $dataObj->replicate();

        $dataObj->admin_id = $this->user->id;

        foreach( $request->summery as $key=>$val ){

            if( $key == "document" && $val != null){

                $folderName = "public/account-summery/".$request->company_id;

                // Create the folder
                Storage::makeDirectory( $folderName );

                // Set permissions to 777
                chmod(storage_path('app/'.$folderName), 0777);

                foreach( $request->summery['document'] as $f=>$file ){

                    $filename = $file->getClientOriginalName();
                    $image = $request->file('summery.document.'.$f);
                    $image->move( storage_path('/app/'.$folderName), $filename );

                    $fileMap = AccountSummeryFileMaps::where( [
                        'company_id' => $request->company_id,
                        'account_summery_id' => $id,
                        'indexing' => $f
                    ] )
                    ->first();

                    if( !$fileMap ){
                        $fileMap = new AccountSummeryFileMaps();

                        $fileMap->account_summery_id = $dataObj->id;
                        $fileMap->company_id = $request->company_id;
                        $fileMap->txn_no = $dataObj->txn_no;
                        $fileMap->indexing = $f;
                    }

                    $fileMap->path = $folderName."/".$filename;
                    $fileMap->filename = $filename;
                    $fileMap->status = 1;
                    $fileMap->save();
                }

                $dataObj->$key = 1;
            } else {
                $dataObj->$key = $val;
            }
        }

        $dataObj->status = 1;
        $dataObj->save();

        //To update all rows in a database table where the date is greater than or equal, and set the is_check_balance = 0
        $this->updateAccountSummeryBalanceData( $oldDataObj->date, $request->company_id, $request->summery['payment_type'] );

        $dataObj = Accounting::find( $id );

        $logArr['description'] = "Account <b>".$dataObj->txn_no."</b> Summery data update successfully";
        $oldPaymentType = $paymentType = "Cash";
        if( $oldDataObj->payment_type > 0 ){
            $oldPaymentType = $oldDataObj->payment->bank_name;
        }

        if( $dataObj->payment_type > 0 ){
            $paymentType = $dataObj->payment->bank_name;
        }

        if( $oldDataObj->payment_type != $dataObj->payment_type ){
            //To update all rows in a database table where the date is greater than or equal, and set the is_check_balance = 0 with old payment type
            $this->updateAccountSummeryBalanceData( $oldDataObj->date, $request->company_id, $oldDataObj->payment_type );
        }

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
                    <td>Username</td>
                    <td>".$oldDataObj->admin->username."</td>
                    <td>".$dataObj->admin->username."</td>
                </tr>
                <tr>
                    <td>Company Details</td>
                    <td>".$oldDataObj->company->sort_name."</td>
                    <td>".$dataObj->company->sort_name."</td>
                </tr>
                <tr>
                    <td>Client Account</td>
                    <td>".$oldDataObj->client_company->name."</td>
                    <td>".$dataObj->client_company->name."</td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td>".$oldPaymentType."</td>
                    <td>".$paymentType."</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>".$oldDataObj->date."</td>
                    <td>".$dataObj->date."</td>
                </tr>
                <tr>
                    <td>Debit Amount</td>
                    <td>".$oldDataObj->debit_amount."</td>
                    <td>".$dataObj->debit_amount."</td>
                </tr>
                <tr>
                    <td>Credit Amount</td>
                    <td>".$oldDataObj->credit_amount."</td>
                    <td>".$dataObj->credit_amount."</td>
                </tr>
                <tr>
                    <td>Balance</td>
                    <td>".$oldDataObj->balance."</td>
                    <td>".$dataObj->balance."</td>
                </tr>
                <tr>
                    <td>CRM Update</td>
                    <td>".( ( $oldDataObj->crm_update == 1 ) ? 'Yes' : 'No' )."</td>
                    <td>".( ( $dataObj->crm_update == 1 ) ? 'Yes' : 'No' )."</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>".$oldDataObj->description."</td>
                    <td>".$dataObj->description."</td>
                </tr>
                <tr>
                    <td>Remarks</td>
                    <td>".$oldDataObj->remarks."</td>
                    <td>".$dataObj->remarks."</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>".$oldDataObj->updated_at."</td>
                    <td>".$dataObj->updated_at."</td>
                </tr>
            ";

        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', 'Record has been updated !!');

        Cache::forget( 'accounting_fields_' . $request->company_id );

        return redirect()->route('company-account-summery-index', [ 'id' => $request->company_id, 'currentPage' => $request->currentPage ] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, int $id)
    {
        $logArr['admin_id'] = $this->user->id ?? 0;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "D";

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'accounting', 'delete' )) {
            $logArr['description'] = "Unauthorized try to delete account summery data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete Account Summery !');
        }

        $record = Accounting::find($id);
        if (!is_null($record)) {

            //To update all rows in a database table where the id is greater than $id and set the is_check_balance = 0
            $this->updateAccountSummeryBalanceData( $record->date, $record->company_id, $record->payment_type );

            $record->delete();

            $logArr['description'] = "'".$record->txn_no.'" has been successfully deleted.';
            saveAdminLog( $logArr );// Save Access log history

            return response()->json( ['data' => ['message' => $logArr['description'] ] ], 200);
        }

        $logArr['description'] = "Something want wrong.";
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => "Something went wrong." ] ], 200);
    }

    /**
     *
     */
    public function updateAccountSummeryBalanceData( $targetDate, $company_id, $payment_type=null ){

        $targetDate = getLastValidDate( $targetDate );//Carbon::parse( $oldDataObj->date )->subDay(); // or Carbon::now()->subDay()

        // Accounting::
        // whereDate( 'date', '>=', $targetDate )
        // ->where( [
        //     'company_id' => $company_id,
        //     'status' => 1,
        //     // 'payment_type' => $payment_type
        // ] )
        // ->update([
        //     'is_check_balance' => 0
        // ]);

        $query = Accounting::whereDate('date', '>=', $targetDate)
            ->where([
                'company_id' => $company_id,
                'status' => 1,
            ]);

        if (!is_null($payment_type)) {
            $query->where('payment_type', $payment_type);
        }

        $query->update([
            'is_check_balance' => 0
        ]);

        //To recursively fetch and update one record at a time in Laravel until no more records are left, you can use a while loop.
        updateAccountSummeryBalance( $company_id, $payment_type );
    }
}
