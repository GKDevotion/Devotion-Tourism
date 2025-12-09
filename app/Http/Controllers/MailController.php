<?php

namespace App\Http\Controllers;

use App\Mail\AdminPersonalMeetingReminderMail;
use App\Mail\ClientScheduleReminderNoteNotificationMail;
use App\Mail\EmployeeAuthAccess;
use App\Mail\LeaveApplicationNotification;
use App\Mail\NewEmployeeRegister;
use App\Models\Admin;
use App\Models\City;
use App\Models\Company;
use App\Models\Person;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Department;
use App\Models\EmployeeType;
use App\Models\HumanHealthCondition;
use App\Models\Industry;
use App\Models\Leave;
use App\Models\Location;
use App\Models\MaritalStatus;
use App\Models\Notification;
use App\Models\PaymentFrequency;
use App\Models\PersonMeeting;
use App\Models\Position;
use App\Models\Religion;
use App\Models\ScheduleList;
use App\Models\Shift;
use App\Models\SocialMediaPlatform;
use App\Models\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function __construct()
    {

    }

    /**
     *
     */
    public function index() {

    }

    /**
     * send every 5 min to new employee complete registration mail
     */
    public function sendEmployeeRegistrationMail(){
        $employeeObjs = Person::where( [
            'status' => 1,
            'type' => 1
        ] )
        ->select( 'id', 'first_name', 'last_name', 'email_id', 'isSendRegisterMail' )
        ->get();

        if( $employeeObjs ){
            foreach( $employeeObjs as $emp ){
                try{

                    //get Company Details
                    $companyObj = Company::select( "name")->where( 'id', $emp->company_id )->first();

                    $data = [
                        'name' => $emp->first_name." ".$emp->last_name,
                        'company_name' => $companyObj->name,
                        'register_link' => url('complete-register/'._en( $emp->id ) ),
                    ];

                    if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                        Mail::to( $emp->email_id )->send( new NewEmployeeRegister( $data ) );
                    }

                    $emp->isSendRegisterMail = 1;
                    $emp->save();
                } catch( Exception $e ){

                    $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $emp->email_id." ".$e->getMessage(). PHP_EOL;
                    Storage::append('employee/cronRegisterMail.log', $message );

                    $emp->isSendRegisterMail = 0;
                    $emp->save();
                }
            }
        }
    }

    /**
     * send every 5 min to new employee complete registration mail
     */
    public function sendEmployeeAuthAccessMail(){

        $userObjs = User::where( [
            'status' => 1,
            'type' => 1,
            'isSendAuthMail' => 0
        ] )
        ->select( 'id', 'name', 'email', 'company_id', 'isSendAuthMail', 'remember_token' )
        ->get();

        if( $userObjs ){
            foreach( $userObjs as $user ){
                try{

                    //get Company Details
                    $companyObj = Company::select( "name")->where( 'id', $user->company_id )->first();

                    $data = [
                        'name' => $user->name,
                        'company_name' => $companyObj->name,
                        'password' => $user->remember_token,
                    ];

                    if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                        Mail::to( $user->email )->send( new EmployeeAuthAccess( $data ) );
                    }

                    $user->isSendAuthMail = 1;
                    $user->save();
                } catch( Exception $e ){

                    $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $user->email.", Password: ".$user->remember_token." ".$e->getMessage(). PHP_EOL;
                    Storage::append('employee/employeeAuthAccess.log', $message );

                    $user->isSendAuthMail = 0;
                    $user->save();
                }
            }
        }
    }

    /**
     * Complete Employee registration
     */
    public function completeEmployeeRegistration( Request $request, $id=0 ){

        $id = _de( $id );

        $checkUser = User::select('id')->where( 'person_id', $id )->first();

        if( $checkUser ){
            return view('thank-you');
        } else {
            $personInfo = Person::find( $id );
            if ( $request->isMethod('get') ) { // Handle GET request

                $isHide = 1;

                $religionArr = Religion::select('id', 'name')->where( [ 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
                $continentArr = Continent::select('id', 'name')->where( [ 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
                $countryArr = Country::select('id', 'name')->where( [ 'status' => 1, 'continent_id' => $personInfo->continent_id ] )->orderBy( 'name', 'ASC' )->get();
                $stateArr = State::select('id', 'name')->where( [ 'status' => 1, 'country_id' => $personInfo->country_id ] )->orderBy( 'name', 'ASC' )->get();
                $cityArr = City::select('id', 'name')->where( [ 'status' => 1, 'state_id' => $personInfo->state_id ] )->orderBy( 'name', 'ASC' )->get();
                $industryArr = Industry::select('id', 'name')->where( [ 'status' => 1] )->orderBy( 'name', 'ASC' )->get();
                $companyArr = Company::select('id', 'name')->where( [ 'status' => 1, 'industry_id' => $personInfo->industry_id ] )->orderBy( 'name', 'ASC' )->get();
                $departmentArr = Department::select('id', 'name')->where( [ 'status' => 1, 'company_id' => $personInfo->company_id ] )->orderBy( 'name', 'ASC' )->get();
                $socialMediaArr = SocialMediaPlatform::select('id', 'name')->where( [ 'status' => 1] )->orderBy( 'name', 'ASC' )->get();

                $jobRoleArr = Position::select('id', 'name', 'parent_id')->where( [ 'status' => 1, 'parent_id' => 0] )->orderBy( 'name', 'ASC' )->get();
                $selectParentJob = Position::select('id', 'name', 'parent_id')->where( [ 'status' => 1, 'id' => $personInfo->position_id] )->first();
                $positionArr = Position::select('id', 'name', 'parent_id')->where( [ 'status' => 1, 'parent_id' => $selectParentJob->parent->id] )->orderBy( 'name', 'ASC' )->get();
                $locationArr = Location::select('id', 'name', 'display_name')->where( [ 'status' => 1] )->orderBy( 'name', 'ASC' )->get();

                $employeeTypeArr = EmployeeType::select('id', 'name')->orderBy( 'name', 'ASC' )->get();
                $payFrequencyArr = PaymentFrequency::select('id', 'name')->orderBy( 'name', 'ASC' )->get();
                $maritalStatusArr = MaritalStatus::select('id', 'name')->orderBy( 'name', 'ASC' )->get();

                $illnessArr = HumanHealthCondition::select('id', 'name')->where( [ 'status' => 1, 'parent_id' => 0] )->orderBy( 'name', 'ASC' )->get();

                $selectParentIllness = $childIllnessArr = [];

                if( $personInfo->personal_information->health_condition_id ){
                    $selectParentIllness = HumanHealthCondition::select('id', 'name', 'parent_id')->where( [ 'status' => 1, 'id' => $personInfo->personal_information->health_condition_id] )->first();
                    $childIllnessArr = HumanHealthCondition::select('id', 'name')->where( [ 'status' => 1, 'parent_id' => $selectParentIllness->parent_id] )->orderBy( 'name', 'ASC' )->get();
                }

                $shiftArr = Shift::select('id', 'name')->orderBy( 'name', 'ASC' )->get();
                return view('frontend.pages.employees.complete-registration', compact( 'personInfo', 'religionArr', 'continentArr', 'industryArr', 'socialMediaArr', 'positionArr', 'employeeTypeArr', 'payFrequencyArr', 'maritalStatusArr', 'illnessArr', 'countryArr', 'stateArr', 'cityArr', 'companyArr', 'departmentArr', 'jobRoleArr', 'selectParentJob', 'childIllnessArr', 'selectParentIllness', 'shiftArr', 'isHide', 'locationArr' ));

            } else { // Handle POST request

                if( $request->step == 1 ){

                    $request->validate([
                        'first_name' => 'required',
                        'middle_name' => 'required',
                        'last_name' => 'required',
                        'email_id' => 'required|email',
                        'personal_mobile_number' => 'required|min:5',
                    ]);

                    return [ 'type' => 'success', 'unique_id' => $personInfo->id,  'message' => $personInfo->first_name.' '.$personInfo->last_name.' has been updated !!', 'status_code' => 200, 'isRedirectThankYou' => false ];
                }

                if( $request->step == 2 ){

                    $request->validate([
                        'industry_id' => 'required',
                        'company_id' => 'required',
                        'department_id' => 'required',
                        'job_role_id' => 'required',
                        'position_id' => 'required',
                        'employee_type_id' => 'required',
                        'joining_date' => 'required',
                        'hire_date' => 'required',
                        'payment_frequency_id' => 'required',
                        'shift_id' => 'required',
                        'location_id' => 'required',
                    ]);

                    return [ 'type' => 'success', 'unique_id' => $personInfo->id,  'message' => $personInfo->first_name.' '.$personInfo->last_name.' has been updated !!', 'status_code' => 200, 'isRedirectThankYou' => false ];
                }

                if( $request->step == 3 ){
                    $request->validate([
                        'basic_salary' => 'required',
                        'gross_salary' => 'required',
                        'monthly_hour' => 'required',
                    ]);

                    return [ 'type' => 'success', 'unique_id' => $personInfo->id,  'message' => $personInfo->first_name.' '.$personInfo->last_name.' has been updated !!', 'status_code' => 200, 'isRedirectThankYou' => false ];
                }

                if( $request->step == 4 ){
                    $request->validate([
                        'religion_id' => 'required',
                        'birth_date' => 'required',
                        'marital_status' => 'required',
                        'blood_group' => 'required',
                        'address' => 'required',
                        'continent_id' => 'required',
                        'country_id' => 'required',
                        'state_id' => 'required',
                        'city_id' => 'required',
                        'zipcode' => 'required'
                    ]);
                }

                if( $request->step == 5 ){
                    $request->validate([
                        'bank_name' => 'required',
                        'holder_name' => 'required',
                        'account_no' => 'required',
                        'ifsc_code' => 'required',
                        'branch_code' => 'required',
                        'bank_address' => 'required',
                    ]);
                }

                if( $request->step == 8 ){
                    $request->validate([
                        'family_doctor_name' => 'required',
                        'doctor_contact_number' => 'required',
                        'clinic_name' => 'required',
                        'clinic_address' => 'required',
                    ]);
                }

                $personDataObj = storeEmployeeRecord( $request, $personInfo->admin_id, $personInfo->user_id, $personInfo->type, $personInfo->id, 0 );

                return response()->json( $personDataObj );
            }
        }
    }

    /**
     * Thank You page
     */
    public function thankYou(){
        return view('thank-you');
    }

    /**
     * send client reminder notification.
     * tbl: schedule_lists
     */
    public function sendClientReminderNoteNotificationMail(){

        $today = Carbon::today();

        $scheduleArrs = ScheduleList::whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->get();

        if ( $scheduleArrs ) {

            $adminArr = [];

            foreach( $scheduleArrs as $schedule ){

                if( isset( $adminArr[$schedule->admin_id] ) ){
                    $adminArr[$schedule->admin_id].= "<tr><td style='border: 1px solid;'>".$schedule->title."</td><td style='border: 1px solid;'>".$schedule->description."</td></td></tr>";
                } else {
                    $adminArr[$schedule->admin_id] = "<tr><td style='border: 1px solid;'>".$schedule->title."</td><td style='border: 1px solid;'>".$schedule->description."</td></td></tr>";
                    $adminArr[$schedule->admin_id].= "<tr><td style='border: 1px solid;'>".$schedule->title."</td><td style='border: 1px solid;'>".$schedule->description."</td></td></tr>";
                }
            }

            foreach( $adminArr as $id => $tbl )
            {
                $table = "<table style='width: 100%;border: 1px solid;'><tr><th style='border: 1px solid;'>title</th><th style='border: 1px solid;'>description</th></td></tr><tbody>";
                $table.= $tbl;
                $table.= "</tbody></table>";

                $adminObj = Admin::select('username', 'email')->find( $id );
                try{
                    $data = [
                        'name' => $adminObj->username,
                        'link' => url('admin'),
                        'table' => $table,
                    ];

                    if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                        Mail::to( $adminObj->email )->send( new ClientScheduleReminderNoteNotificationMail( $data ) );
                    }
                } catch( Exception $e ){
                    $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $adminObj->email." ".$e->getMessage(). PHP_EOL;
                    Storage::append('client/scheduleReminderNoteNotificationMail.log', $message );
                }

            }
        }
    }

    /**
     * send every 5 min to new employee complete registration mail
     */
    public function sendNewLeaveApplicationMail(){

        $leaveObjs = Leave::where([
            'is_send' => 0
        ])
        ->get();

        if( $leaveObjs ){
            foreach( $leaveObjs as $leave ){
                $employeeObjs = Person::select('first_name', 'last_name')->find( $leave->person_id );

                if( $employeeObjs ){
                    try{
                        $data = [
                            'id' => $leave->id,
                            'name' => $employeeObjs->first_name." ".$employeeObjs->last_name,
                            'leave_type' => $leave->leave_type->name,
                            'start_date' => $leave->from_date,
                            'end_date' => $leave->end_date,
                            'reason' => $leave->reason
                        ];

                        if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                            Mail::to( "careers@devotiongroup.org" )->send( new LeaveApplicationNotification( $data ) );
                        }

                        $leave->is_send = 1;
                        $leave->save();

                        //set admin dashboard notification
                        $notificationObj = new Notification();
                        $notificationObj->table_id = $leave->id;
                        $notificationObj->title = "New leave application for ".$employeeObjs->first_name." ".$employeeObjs->last_name;
                        $notificationObj->description = "Type: ".$leave->leave_type->name.", Start Date: ".$leave->from_date.", End Date: ". $leave->end_date.", Reason: ".$leave->reason;
                        $notificationObj->status = 0;
                        $notificationObj->type = "LEAVE";
                        $notificationObj->save();

                    } catch( Exception $e ){

                        $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $employeeObjs->email_id." ".$e->getMessage(). PHP_EOL;
                        Storage::append('leave/cronLeaveMail.log', $message );

                        $leave->is_send = 0;
                        $leave->save();
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function replyLeaveApplicationMail( $leave ){
        $employeeObjs = Person::select('first_name', 'last_name', 'email_id')->find( $leave->person_id );

        if( $employeeObjs ){
            try{
                $data = [
                    'id' => $leave->id,
                    'name' => $employeeObjs->first_name." ".$employeeObjs->last_name,
                    'reason' => $leave->reason,
                    're_mark' => $leave->re_mark,
                    'status' => $leave->status == 1 ? "Approved" : "Reject"
                ];

                if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                    Mail::to( $employeeObjs->email_id )->send( new LeaveApplicationNotification( $data ) );
                }

                $leave->is_send = 1;
                $leave->save();
            } catch( Exception $e ){

                $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $employeeObjs->email_id." ".$e->getMessage(). PHP_EOL;
                Storage::append('leave/cronLeaveMail.log', $message );

                $leave->is_send = 0;
                $leave->save();
            }
        }
    }

    /**
     * admin reminder mail for available client meeting.
     * tbl: person_meetings
     */
    public function sendPersonalMeetingAdminMail(){
        $today = Carbon::today();

        $meetingReminders = PersonMeeting::where('client_id', '>', 0)
                    ->where( 'isSendMail', 0 )
                    ->whereDate('follow_up_date', '<=', $today)
                    ->whereDate('follow_up_date', '>=', $today)
                    ->get();

        if ( $meetingReminders ) {

            //founder@devotiongroup.org
            if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                Mail::to( "gk@mailinator.com" )->send( new AdminPersonalMeetingReminderMail( $meetingReminders ) );
            }
        }
    }

    /**
     * admin reminder mail for available client meeting.
     * tbl: person_meetings
     */
    public function sendCompanyMeetingAdminMail(){
        $today = Carbon::today();

        $meetingReminders = PersonMeeting::where('status', '>', 0)
                    ->where( 'isSendMail', 0 )
                    ->whereDate('follow_up_date', '<=', $today)
                    ->whereDate('follow_up_date', '>=', $today)
                    ->get();

        if ( $meetingReminders ) {

            $mailType = "Company ";
            //founder@devotiongroup.org
            if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                Mail::to( "gk@mailinator.com" )->send( new AdminPersonalMeetingReminderMail( $meetingReminders, $mailType ) );
            }
        }
    }
}
