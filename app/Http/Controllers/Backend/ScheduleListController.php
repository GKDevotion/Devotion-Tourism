<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ScheduleList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleListController extends Controller
{
    public $user;
    public $is_assign_super_admin = 0;
    public $admin_id = 0;
    public $personType = 3;// 1: Employee, 2: Customer, 3: Client
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
    public function setPublicVar(){
        $this->is_assign_super_admin = $this->user->is_assign_super_admin;
        $this->admin_id = $this->user->id;
    }
    
    public function index()
    {
        return view('calendar');
    }

    public function loadScheduleList(Request $request)
    {
        $scheduleLists = ScheduleList::all();
        return response()->json($scheduleLists);
    }

    public function store(Request $request)
    {
        $scheduleList = ScheduleList::create([
            'title' => $request->input('title'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);

        return response()->json($scheduleList);
    }

    public function update(Request $request, $id)
    {
        $scheduleList = ScheduleList::findOrFail($id);
        $scheduleList->update([
            'title' => $request->input('title'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);

        return response()->json($scheduleList);
    }

    public function destroy($id)
    {
        $scheduleList = ScheduleList::findOrFail($id);
        $scheduleList->delete();

        // return response()->json(['status' => 'Event deleted']);
        return response()->json( ['data' => ['message' => "'".$scheduleList->title.'" has been successfully deleted.' ] ], 200);
    }

    /**
     * 
     */
    public function clientReminderNotes(Request $request)
    {

        $this->setPublicVar();

        $scheduleList = new ScheduleList();
        $scheduleList->admin_id = $this->admin_id;
        $scheduleList->title = $request->title;
        $scheduleList->start_date = $request->start_date;
        $scheduleList->end_date = $request->end_date;
        $scheduleList->description = $request->description;
        $scheduleList->type = $request->type;
        $scheduleList->save();

        session()->flash('success', $request->title.' has been created !!');
        return redirect()->route('admin.client.index');
    }
}
