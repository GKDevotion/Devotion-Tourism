<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\NoticeBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class NoticeBoardController extends Controller
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
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('notice-board.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any notice board !');
        }

        return view('backend.pages.notice_board.index');
    }

    /**
     *
     */
    public function ajaxIndex(){

        $query = NoticeBoard::query();

        $query->select('id', 'type', 'description', 'date', 'notice_by', 'status', 'updated_at');
        return DataTables::eloquent($query)
            ->addColumn('id', function(NoticeBoard $obj) {
                return $obj->id;
            })
            ->addColumn('type', function(NoticeBoard $obj) {
                return $obj->type;
            })
            ->addColumn('description', function(NoticeBoard $obj) {
                return $obj->description;
            })
            ->addColumn('date', function(NoticeBoard $obj) {
                return $obj->date;
            })
            ->addColumn('notice_by', function(NoticeBoard $obj) {
                return $obj->notice_by;
            })
            ->addColumn('status', function(NoticeBoard $obj) {
                $status = "";
                if( true ){
                    $status = '<i class="fa fa-'.( $obj->status == 0 ? 'times' : 'check').' update-status" data-status="'.$obj->status.'" data-id="'.$obj->id.'" aria-hidden="true" data-table="notice_boards"></i>';
                } else {
                 $status = '<select class="form-control update-status badge '.( $obj->status == 0 ? 'bg-warning' : 'bg-success').' text-white" name="status" data-id="'.$obj->id.'" data-table="notice_boards">
                            <option value="1" '.($obj->status == 1 ? 'selected' : '').'>Active</option>
                            <option value="0" '.($obj->status == 0 ? 'selected' : '').'>De-Active</option>
                        </select>';
                }

                return $status;
            })
            ->addColumn('updated_at', function(NoticeBoard $obj) {
                return formatDate( "Y-m-d H:i", $obj->updated_at );
            })
            ->addColumn('action', function(NoticeBoard $obj ) {

                $action = '
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_'.$obj->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#x22EE;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="action_menu_'.$obj->id.'">
                    ';

                    if ($this->user->can('notice-board.edit')) {
                        $action.= '<a class="btn btn-edit text-white dropdown-item" href="'.route('admin.notice-board.edit', $obj->id).'">
                            <i class="fa fa-pencil"></i> Edit
                        </a>';
                    }

                    if ($this->user->can('notice-board.delete')) {
                        $action.= '<button class="btn btn-edit text-white dropdown-item delete-record" data-id="'.$obj->id.'" data-title="'.$obj->name.'" data-segment="notice-board">
                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                    </button>';
                    }

                    $action.= '
                    </div>
                ';

                return $action;
            })
            ->rawColumns(['id', 'type', 'date', 'description', 'notice_by', 'updated_at', 'status', 'action'])  // Specify the columns that contain HTML
            ->filter(function ($query) {
                if (request()->has('search')) {
                    $searchValue = request('search')['value'];
                    $query->where('date', 'like', "%{$searchValue}%")
                        ->where('type', 'like', "%{$searchValue}%")
                        ->where('description', 'like', "%{$searchValue}%");
                        // ->orWhereHas('person', function($q) use ($searchValue) {
                        //     $q->where('first_name', 'like', "%{$searchValue}%")
                        //     ->where('middle_name', 'like', "%{$searchValue}%")
                        //     ->where('last_name', 'like', "%{$searchValue}%");
                        // });
                        // ->orWhere('email', 'like', "%{$searchValue}%");
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
    public function create()
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('notice-board.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any notice board !');
        }

        return view('backend.pages.notice_board.create');
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

        if (!$this->user->can('notice-board.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any notice board !');
        }

        // Validation Data
        $request->validate([
            'type' => 'required',
            'description' => 'required',
            'date' => 'required',
            'notice_by' => 'required',
        ]);

        $this->setPublicVar();

        // Create New Notice Board
        $objData = new NoticeBoard();
        $objData->admin_id = $this->admin_id;
        $objData->user_id = $this->admin_id;
        $objData->employee_id = $request->employee_id ?? null;
        $objData->type = $request->type;
        $objData->description = $request->description;
        $objData->date = $request->date;
        $objData->notice_by = $request->notice_by;
        $objData->status = $request->status ?? 1;
        $objData->sort_order = $request->sort_order ?? 0;
        $objData->save();

        //save person avtar or passport size photo
        if ($request->hasFile('attachement')) {
            $filename = $objData->unique_id."-".$request->attachement->getClientOriginalName();
            // $request->image->storeAs('blog', $filename, 'public' );

            $folderName = "noticeBoard/".$objData->id;

            // Create the folder
            Storage::makeDirectory( 'public/'.$folderName );

            // Set permissions to 777
            chmod(storage_path('app/public/'.$folderName), 0777);

            $image = $request->file('attachement');
            $destinationPath = storage_path('/app/public/'.$folderName);

            $img = Image::make($image->path());
            $img->resize(400, 820, function ($constraint) {
                // $constraint->aspectRatio();
                // $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $objData->attachement = "public/noticeBoard/".$objData->id."/".$filename;
            $objData->save();
        }

        if( isset( $request->is_ajax ) && $request->is_ajax == 1 ){
            return [ 'type' => 'success', 'message' => 'Notice '.$objData->type.' has been created !!', 'status_code' => 200 ];
        } else {
            session()->flash('success', $objData->type.' has been created !!');
            return redirect()->route('admin.notice-board.index');
        }
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
    public function edit(int $id)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        if (!$this->user->can('notice-board.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any notice board !');
        }

        $data = NoticeBoard::find($id);
        return view('backend.pages.notice_board.edit', compact('data'));
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

        if (!$this->user->can('notice-board.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any notice board !');
        }

        // Validation Data
        $request->validate([
            'type' => 'required',
            'description' => 'required',
            'date' => 'required',
            'notice_by' => 'required',
        ]);

        $this->setPublicVar();

        // Create New Notice Board
        $objData = new NoticeBoard();
        $objData->admin_id = $this->admin_id;
        $objData->user_id = $this->admin_id;
        $objData->type = $request->type;
        $objData->description = $request->description;
        $objData->date = $request->date;
        $objData->notice_by = $request->notice_by;
        $objData->status = $request->status;
        $objData->sort_order = $request->sort_order;
        $objData->save();

        //save person avtar or passport size photo
        if ($request->hasFile('attachement')) {
            $filename = $objData->unique_id."-".$request->attachement->getClientOriginalName();
            // $request->image->storeAs('blog', $filename, 'public' );

            $folderName = $objData->id."/noticeBoard";

            // Create the folder
            Storage::makeDirectory( 'public/'.$folderName );

            // Set permissions to 777
            chmod(storage_path('app/public/'.$folderName), 0777);

            $image = $request->file('attachement');
            $destinationPath = storage_path('/app/public/'.$folderName);

            $img = Image::make($image->path());
            $img->resize(400, 820, function ($constraint) {
                // $constraint->aspectRatio();
                // $constraint->upsize();
            })->save($destinationPath.'/'.$filename);

            $objData->avtar = "public/".$objData->id."/noticeBoard/".$filename;
            $objData->save();
        }

        session()->flash('success', $objData->type.' has been updated !!');
        return redirect()->route('admin.notice-board.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('notice-board.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any notice board !');
        }

        $dataObj = NoticeBoard::find($id);
        if ( $dataObj ) {
            $dataObj->delete();
            return response()->json( ['data' => ['message' => $dataObj->type.' record has been successfully deleted.'] ], 200 );
        } else {
            return response()->json( ['data' => ['message' => 'Record already deleted.'] ], 200);
        }
    }
}
