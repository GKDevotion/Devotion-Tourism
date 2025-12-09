<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountFieldController extends Controller
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
    public function index( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'account-field', 'view')) {
            $logArr['description'] = "Unauthorized try to accounting field data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view Accounting data !');
        }

        $auth = $this->user;
        $logArr['description'] = "Load account field data successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-field.index', compact( 'auth' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $edit = fetchSinglePermission( $this->user, 'admin.company', 'edit');
        $delete = fetchSinglePermission( $this->user, 'admin.company', 'delete');

        $query = AccountField::query();

        $query->select('id', 'name', 'type', 'required', 'status', 'updated_at');

        return DataTables::eloquent($query)
            ->addColumn('id', function(AccountField $ar) {
                return $ar->id;
            })
            ->addColumn('name', function(AccountField $ar) {
                return $ar->name;
            })
            ->addColumn('required', function(AccountField $ar) {
                // return $ar->required;
                return '<i class="fa fa-'.( $ar->required == 0 ? 'times' : 'check').'" data-status="'.$ar->required.'" data-id="'.$ar->id.'" aria-hidden="true" data-table="account_management_fields"></i>';
            })
            ->addColumn('type', function(AccountField $ar) {
                return $ar->type;
            })
            ->addColumn('status', function(AccountField $ar) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $ar->status == 0 ? 'times' : 'check').' update-status" data-status="'.$ar->status.'" data-id="'.$ar->id.'" aria-hidden="true" data-table="account_management_fields"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $ar->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$ar->id.'" data-table="account_management_fields">
                            <option value="1" '.($ar->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($ar->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('updated_at', function(AccountField $ar) {
                return formatDate( "Y-m-d H:i", $ar->updated_at );
            })
            ->addColumn('action', function(AccountField $ar ) use ( $edit, $delete ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$ar->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$ar->id.'">
                    ';

                    if ($edit) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.account-field.edit', $ar->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($delete) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$ar->id.'" data-title="'.$ar->display_name.'" data-segment="locations">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'name', 'required', 'type', 'updated_at', 'status', 'action'])  // Specify the columns that contain HTML
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
    public function create( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "C";

        if (!fetchSinglePermission( $this->user, 'account-field', 'add')) {
            $logArr['description'] = "Unauthorized try to create account field";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create Account Field !');
        }

        $auth = $this->user;
        $logArr['description'] = "Create account field form loaded";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-field.create', compact('auth'));
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

        if (!fetchSinglePermission( $this->user, 'account-field', 'add')) {
            $logArr['description'] = "Unauthorized try to store account field data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to store account Field data!');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $slug = convertStringToSlug( $request->name, '_' );

        $dataObj = new AccountField();
        $dataObj->name = $request->name;
        $dataObj->slug = $slug;
        $dataObj->type = $request->type;
        $dataObj->required = $request->required;
        $dataObj->status = $request->status;
        $dataObj->save();

        $type = "";
        if( $request->type == "textbox" ){
            $type = "VARCHAR(50)";
        } else if( $request->type == "dropdown" || $request->type == "number" ){
            $type = "INT";
        } else if( $request->type == "document" ){
            $type = "VARCHAR(500)";
        } else if( $request->type == "date" ){
            $type = "DATE";
        } else if( $request->type == "textarea" ){
            $type = "TEXT";
        } else if( $request->type == "selection" ){
            $type = "TINYINT";
        } else if( $request->type == "float" ){
            $type = "FLOAT";
        }

        if( $type != "" ){
            DB::statement("ALTER TABLE `account_summeries` ADD COLUMN `".$slug."` ".$type." NULL AFTER `status`");
        }

        $logArr['description'] = $request->name.' record has been created !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.account-field.index');
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

        if (!fetchSinglePermission( $this->user, 'account-field', 'edit') ) {
            $logArr['description'] = "Unauthorized try to edit account field data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Field !');
        }

        $auth = $this->user;
        $data = AccountField::find($id);
        $logArr['description'] = "Load account field form successfully";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.account-field.edit', compact('data', 'auth'));
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

        if (!fetchSinglePermission( $this->user, 'account-field', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update account field data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit Field !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        // Create New Server Record
        $dataObj = AccountField::find( $id );
        $dataObj->name = $request->name;
        $dataObj->type = $request->type;
        $dataObj->required = $request->required;
        $dataObj->status = $request->status;
        $dataObj->save();

        $logArr['description'] = $request->display_name.' record has been updated !!';
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $logArr['description']);
        return redirect()->route('admin.account-field.index');
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

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'account-field', 'delete')) {
            $logArr['description'] = "Unauthorized try to delete account field data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete Field !');
        }

        $dataObj = AccountField::find($id);

        if (!is_null($dataObj)) {
            $dataObj->delete();
        }

        $logArr['description'] = "'".$dataObj->name.'" has been successfully deleted.';
        saveAdminLog( $logArr );// Save Access log history

        return response()->json( ['data' => ['message' => $logArr['description'] ] ], 200);
    }
}
