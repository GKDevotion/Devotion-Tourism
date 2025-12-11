<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    public $user;

    /**
     *
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     *
     */
    public function index( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'admin.company', 'view') ) {
            $logArr['description'] = "Unauthorized to view company data!";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view company data !');
        }

        $auth = $this->user;

        $dataArr = Company::where( 'status', 1 );

        if( $request->cid ){
            $dataArr->where( 'company_id', _de( $request->cid ) );
        } else if( $request->iid ){
            $dataArr->where( 'industry_id', _de( $request->iid ) );
        }

        $dataArr = $dataArr->select('id', 'logo', 'name', 'currency_id', 'website_link', 'contact_number', 'email_id', 'address', 'updated_at', 'sort_name', 'status', 'is_dashboard')
        ->get();

        return view('backend.pages.companies.index', compact( 'request', 'auth', 'dataArr' ));
    }

    /**
     *
     */
    public function _index( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "V";

        if (!fetchSinglePermission( $this->user, 'admin.company', 'view') ) {
            $logArr['description'] = "Unauthorized to view company data!";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to view company data !');
        }

        $auth = $this->user;
        return view('backend.pages.companies.index', compact( 'request', 'auth' ));
    }

    /**
     *
     */
    public function ajaxIndex( Request $request ){

        $edit = fetchSinglePermission( $this->user, 'admin.company', 'edit');
        $delete = fetchSinglePermission( $this->user, 'admin.company', 'delete');
        $addAccount = fetchSinglePermission( $this->user, 'account-management', 'add');
        $addBank = fetchSinglePermission( $this->user, 'bank-information', 'add');
       
        $query = Company::query();
        $query->where( 'status', 1 );

     
        if( $request->cid ){
            $query->where( 'company_id', _de( $request->cid ) );
        } else if( $request->iid ){
            $query->where( 'industry_id', _de( $request->iid ) );
        }

        $query->select( 'id', 'logo', 'name', 'currency_id', 'website_link', 'contact_number', 'email_id', 'address', 'updated_at', 'sort_name', 'status', 'is_dashboard' );
        return DataTables::eloquent($query)
            ->addColumn('id', function(Company $cmp) {
                return $cmp->id;
            })
            ->addColumn('name', function(Company $cmp) use ( $addAccount ) {

                $name = $cmp->name;// Display company name
                if( $addAccount ){
                    $name = '<a class="" href="'.route('company-account-summery-index', $cmp->id).'">
                        '.$name.'
                    </a>';
                }

                return $name;
            })
            ->addColumn('logo', function(Company $cmp) {
                return url( 'storage/'.$cmp->logo );
            })
            ->addColumn('website_link', function(Company $cmp) {
                return $cmp->website_link;
            })
            ->addColumn('sort_name', function(Company $cmp) {
                return $cmp->sort_name;
            })
            ->addColumn('currency', function(Company $cmp) {
                $currencyArr = json_decode( $cmp->currency_id, 1 );
                $currency = "";
                if( is_array( $currencyArr ) ){
                    foreach( $currencyArr as $id ){
                        $currencyObj = Currency::select( 'name' )->find($id);
                        $currency.= $currencyObj->name.", ";
                    }
                }
                return rtrim( $currency, ", ");
            })
            ->addColumn('email_id', function(Company $cmp) {
                return $cmp->email_id;
            })
            ->addColumn('address', function(Company $cmp) {
                return $cmp->address;
            })
            ->addColumn('status', function(Company $cmp) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $cmp->status == 0 ? 'times' : 'check').' update-status" data-status="'.$cmp->status.'" data-id="'.$cmp->id.'" aria-hidden="true" data-table="companies"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $cmp->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$cmp->id.'" data-table="companies">
                            <option value="1" '.($cmp->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($cmp->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('is_dashboard', function(Company $cmp) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $cmp->is_dashboard == 1 ? 'times' : 'check').' update-field-status" data-status="'.$cmp->is_dashboard.'" data-id="'.$cmp->id.'" aria-hidden="true" data-field="is_dashboard" data-table="companies"></i>';
                } else {
                 $status = '<select class="form-control update-field-status badge '.( $cmp->is_dashboard == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$cmp->id.'" data-field="is_dashboard" data-table="companies">
                            <option value="1" '.($cmp->is_dashboard == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($cmp->is_dashboard == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('assign_user', function(Company $cmp) {
                $assignUser = "";

                foreach( $cmp->adminmap as $am ){
                    $assignUser.= $am->admin->username." (".$am->admin->acc_no."),<br>";
                }

                return rtrim( $assignUser, ",<br>" );
            })
            ->addColumn('updated_at', function(Company $cmp) {
                return formatDate( "Y-m-d H:i", $cmp->updated_at );
            })
            ->addColumn('action', function(Company $cmp ) use ($edit, $delete, $addAccount, $addBank) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$cmp->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$cmp->id.'">
                        ';

                        if ( $edit && auth()->guard('admin')->user()->admin_user_group_id == 1 ) {
                            $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.company.edit', $cmp->id).'">
                                <i class="fa fa-pencil"></i> Edit
                            </a>';

                            $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.company-account-field-map.update', $cmp->id).'">
                                <i class="fa fa-columns"></i> Field Mapping
                            </a>';
                        }

                        if( $addAccount ){
                            $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('company-account-management-index', $cmp->id).'">
                                <i class="fa fa-building-o"></i> Client Company(s)
                            </a>';
                        }

                        if( $addBank ){
                            $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('company-bank-information-index', $cmp->id).'">
                                <i class="fa fa-university"></i> Bank Account
                            </a>';
                        }

                        if( $addAccount ){
                            $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('company-account-summery-index', $cmp->id).'">
                                <i class="fa fa-file-archive-o"></i> Account Summery
                            </a>';
                        }

                        if ( $delete ) {
                            $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$cmp->id.'" data-title="'.$cmp->name.'" data-segment="companies">
                                            <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                        </button>';
                        }

                        $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'logo', 'favicon', 'name', 'currency', 'website_link', 'email_id', 'currency', 'address', 'updated_at', 'sort_name', 'status', 'is_dashboard', 'assign_user', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    if( $searchValue != "" ){
                        $query->where('name', 'like', "%{$searchValue}%")
                            ->orWhereHas('industry', function($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%");
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
    public function create( Request $request )
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "C";

        if (!fetchSinglePermission( $this->user, 'admin.company', 'add') ) {
            $logArr['description'] = "Unauthorized try to create company";
            saveAdminLog( $logArr );// Save Access log history
            abort(403, 'Sorry !! You are Unauthorized to create company data !');
        }

        $auth = $this->user;
        $industries = Industry::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();
        $companies = Company::select( 'id', 'name' )->where( 'parent_id', 0 )->orderBy( 'name', 'ASC' )->get();
        $currency = Currency::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();
        $adminArr = Admin::select( 'id', 'acc_no', 'username' )->where( 'status', 1 )->orderBy( 'username', 'ASC' )->get();

        $logArr['description'] = "Try to create new company";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.companies.create', compact( 'industries', 'companies', 'currency', 'auth', 'adminArr' ));
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

        if (!fetchSinglePermission( $this->user, 'admin.company', 'add') ) {
            $logArr['description'] = "Unauthorized to stored company details";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to create company data !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100',
            'website_link' => 'required|max:50',
            'industry_id' => 'required',
            'sort_name' => 'required',
            'address' => 'required',
            'email_id' => 'required',
            'currency_id' => 'required',
            'admin_id' => 'required',
        ]);

        $slug = convertStringToSlug( $request->name );
        $dataObj = new Company();
        $dataObj->admin_id = $this->user->id;
        $dataObj->name = $request->name;
        $dataObj->website_link = $request->website_link;
        $dataObj->industry_id = $request->industry_id;
        $dataObj->status = $request->status;
        $dataObj->parent_id = $request->parent_id;
        $dataObj->sort_order = $request->sort_order ?? 0;
        $dataObj->sort_name = $request->sort_name;
        $dataObj->email_id = $request->email_id;
        $dataObj->contact_number = $request->contact_number;
        $dataObj->slug = $slug;
        $dataObj->currency_id = json_encode( $request->currency_id, 1 );

        //save company logo
        if ($request->hasFile('logo')) {
            $filename = $slug."-logo-".$request->logo->getClientOriginalName();

            $folderName = "public/companies";

            // Create the folder
            Storage::makeDirectory( $folderName );

            // Set permissions to 777
            chmod(storage_path('app/'.$folderName), 0777);

            $image = $request->file('logo');
            $destinationPath = storage_path('/app/'.$folderName);
            $img = Image::make($image->path());
            $img->resize(800, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $dataObj->logo = 'app/'.$folderName."/".$filename;
        }

        //save company favicon
        if ($request->hasFile('favicon')) {
            $filename = $slug."-favicon-".$request->favicon->getClientOriginalName();

            $folderName = "public/companies";

            // Create the folder
            Storage::makeDirectory( $folderName );

            // Set permissions to 777
            chmod(storage_path('app/'.$folderName), 0777);

            $image = $request->file('favicon');
            $destinationPath = storage_path('/app/'.$folderName);
            $img = Image::make($image->path());
            $img->resize(800, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $dataObj->favicon = 'app/'.$folderName."/".$filename;
        }

        $dataObj->save();

        $this->mapCompanyAdmin( $request->admin_id, $dataObj->id );

        $logArr['description'] = $dataObj->name."(".$dataObj->sort_name.") has been created";
        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $dataObj->name.' has been created !!');
        return redirect()->route('admin.company.index');
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
    public function edit( Request $request,  $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "E";

        if (!fetchSinglePermission( $this->user, 'admin.company', 'edit') ) {
            $logArr['description'] = "Unauthorized to edit company data";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit company data !');
        }

        $auth = $this->user;
        $data = Company::find($id);
        $industries = Industry::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();
        $companies = Company::select( 'id', 'name' )->where( 'parent_id', 0 )->orderBy( 'name', 'ASC' )->get();
        $currency = Currency::select( 'id', 'name' )->where( 'status', 1 )->orderBy( 'name', 'ASC' )->get();
        $adminArr = Admin::select( 'id', 'acc_no', 'username' )->where( 'status', 1 )->orderBy( 'username', 'ASC' )->get();
       
        $logArr['description'] = "Load company: ".$data->name." data";
        saveAdminLog( $logArr );// Save Access log history

        return view('backend.pages.companies.edit', compact( 'data', 'industries', 'companies', 'currency', 'auth', 'adminArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "U";

        if (!fetchSinglePermission( $this->user, 'admin.company', 'edit') ) {
            $logArr['description'] = "Unauthorized try to update company";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to edit company data !');
        }

        $request->validate([
            'name' => 'required|max:100',
            'website_link' => 'required|max:50',
            'industry_id' => 'required',
            'sort_name' => 'required',
            'email_id' => 'required',
            'currency_id' => 'required',
        ]);

        $slug = convertStringToSlug( $request->name );

        // Create New User
        $dataObj = Company::find( $id );
        $oldDataObj = clone $dataObj;

        $dataObj->name = $request->name;
        $dataObj->website_link = $request->website_link;
        $dataObj->industry_id = $request->industry_id;
        $dataObj->status = $request->status;
        $dataObj->parent_id = $request->parent_id;
        $dataObj->sort_order = $request->sort_order;
        $dataObj->sort_name = $request->sort_name;
        $dataObj->email_id = $request->email_id;
        $dataObj->slug = $slug;
        $dataObj->contact_number = $request->contact_number;
        $dataObj->currency_id = json_encode( $request->currency_id, 1 );

        //save company logo
        if ($request->hasFile('logo')) {
            $filename = $slug."-logo-".$request->logo->getClientOriginalName();

            $folderName = "public/companies";

            // Create the folder
            Storage::makeDirectory( $folderName );

            // Set permissions to 777
            chmod(storage_path('app/'.$folderName), 0777);

            $image = $request->file('logo');
            $destinationPath = storage_path('/app/'.$folderName);
            $img = Image::make($image->path());
            $img->resize(800, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $dataObj->logo = 'app/'.$folderName."/".$filename;
        }

        //save company favicon
        if ($request->hasFile('favicon')) {
            $filename = $slug."-favicon-".$request->favicon->getClientOriginalName();

            $folderName = "public/companies";

            // Create the folder
            Storage::makeDirectory( $folderName );

            // Set permissions to 777
            chmod(storage_path('app/'.$folderName), 0777);

            $image = $request->file('favicon');
            $destinationPath = storage_path('/app/'.$folderName);
            $img = Image::make($image->path());
            $img->resize(800, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $dataObj->favicon = 'app/'.$folderName."/".$filename;
        }

        $dataObj->save();

        $this->mapCompanyAdmin( $request->admin_id, $dataObj->id );

        $logArr['description'] = $dataObj->name.' ( '.$dataObj->sort_name.' ) has been updated';

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
                    <td>Sort Name</td>
                    <td>".$oldDataObj->sort_name."</td>
                    <td>".$dataObj->sort_name."</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>".$oldDataObj->email_id."</td>
                    <td>".$dataObj->email_id."</td>
                </tr>
                <tr>
                    <td>Website</td>
                    <td>".$oldDataObj->website_link."</td>
                    <td>".$dataObj->website_link."</td>
                </tr>
                <tr>
                    <td>Number</td>
                    <td>".$oldDataObj->contact_number."</td>
                    <td>".$dataObj->contact_number."</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>".$oldDataObj->updated_at."</td>
                    <td>".$dataObj->updated_at."</td>
                </tr>
            ";

        saveAdminLog( $logArr );// Save Access log history

        session()->flash('success', $dataObj->name.' has been updated !!');
        return redirect()->route('admin.company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $logArr['admin_id'] = $this->user->id ?? 0;;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "D";

        if (is_null($this->user) || !fetchSinglePermission( $this->user, 'admin.company', 'delete') ) {
            $logArr['description'] = "Unauthorized try to delete company";
            saveAdminLog( $logArr );// Save Access log history

            abort(403, 'Sorry !! You are Unauthorized to delete company data !');
        }

        $dataObj = Company::find($id);
        if ( $dataObj ) {
            $dataObj->delete();

            $logArr['description'] = $dataObj->name.' record has been successfully deleted.';
            saveAdminLog( $logArr );// Save Access log history

            return response()->json( ['data' => ['message' => $dataObj->name.' record has been successfully deleted.' ] ], 200);
        } else {

            $logArr['description'] = "Company (".$id.") Record already deleted.";
            saveAdminLog( $logArr );// Save Access log history

            return response()->json( ['data' => ['message' => 'Record already deleted.'], 'status' => 200 ], 200);
        }
    }

}
