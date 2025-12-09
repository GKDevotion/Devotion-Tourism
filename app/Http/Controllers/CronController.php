<?php

namespace App\Http\Controllers;

use App\Http\Resources\AwardResource;
use App\Http\Resources\LeaveResource;
use App\Http\Resources\PayrollResource;
use App\Mail\NewEmployeeRegister;
use App\Models\Address;
use App\Models\AdminLog;
use App\Models\Attendance;
use App\Models\Award;
use App\Models\BusinessType;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCorporateUser;
use App\Models\ClientEmployeeUser;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Department;
use App\Models\Industry;
use App\Models\Leave;
use App\Models\NoticeBoard;
use App\Models\Payroll;
use App\Models\Permission;
use App\Models\Person;
use App\Models\PersonPersonalInformation;
use App\Models\Portfolio;
use App\Models\Religion;
use App\Models\State;
use App\Services\ActivityLogService;
use App\User;
use Carbon\Carbon;
use EchoLabs\Prism\Prism;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
    }

    /**
     * Add Religions data
     */
    public function addReligion()
    {
        $arr = [
            'African Traditional & Diasporic',
            'Agnostic',
            'Atheist',
            'Baha\'i',
            'Buddhism',
            'Cao Dai',
            'Chinese traditional religion',
            'Christianity',
            'Hinduism',
            'Islam',
            'Jainism',
            'Juche',
            'Judaism',
            'Neo Paganism',
            'Nonreligious',
            'Rastafarianism',
            'Secular',
            'Shinto',
            'Sikhism',
            'Spiritism',
            'Tenrikyo',
            'Unitarian Universalism',
            'Zoroastrianism',
            'Primal Indigenous',
            'Other'
        ];

        foreach ($arr as $ar) {
            $reg = new Religion();
            $reg->name = $ar;
            $reg->save();
        }
    }

    /**
     *
     */
    public function updateContinent()
    {
        $getCountries = Country::select('id', 'continent_id')->get();

        foreach ($getCountries as $country) {
            State::where('country_id', $country->id)->update(['continent_id' => $country->continent_id]);
            City::where('country_id', $country->id)->update(['continent_id' => $country->continent_id]);
        }

        echo "Success";
    }

    /**
     *
     */
    public function updateCountry()
    {
        $getCountries = Country::select('id', 'name', 'slug')->get();

        foreach ($getCountries as $country) {
            $country->slug = convertStringToSlug($country->name);
            $country->save();
        }

        echo "Success";
    }

    /**
     *
     */
    public function updateState()
    {
        $getState = State::select('id', 'name', 'slug')->get();

        foreach ($getState as $state) {
            $state->slug = convertStringToSlug($state->name);
            $state->save();
        }

        echo "Success";
    }

    /**
     *
     */
    public function updateCity()
    {
        $getCity = City::select('id', 'name', 'slug')->take(20000)->skip(160000)->get();

        foreach ($getCity as $city) {
            $city->slug = convertStringToSlug($city->name);
            $city->save();
        }

        echo "Success";
    }

    /**
     *
     */
    public function getContinent()
    {
        return getContinents();
    }

    /**
     *
     */
    public function getCountryByContinentID($continent_id = null)
    {
        return getCountryByContinentID($continent_id);
    }

    /**
     *
     */
    public function getStateByCountryID($country_id = null)
    {
        return getStateByCountryID($country_id);
    }

    /**
     *
     */
    public function getCityByStateByID($state_id = null)
    {
        return getCityByStateByID($state_id);
    }

    /**
     *
     */
    public function getSocialMediaPlatform()
    {
        return getSocialMediaPlatform();
    }

    /**
     *
     */
    public function updateIndustryHexCode()
    {
        $dataObj = Industry::all();

        foreach ($dataObj as $data) {
            $hexCode = generateRandomHexColor();
            $rgbCode = hexToRgb($hexCode);

            $data->hax_code = $hexCode;
            $data->rgb_code = $rgbCode;
            $data->save();
        }
    }

    /**
     *
     */
    public function updateCompany()
    {
        $getCompanies = Company::select('id', 'hax_code', 'rgb_code')->get();

        foreach ($getCompanies as $company) {
            $hax_code = generateRandomHexColor();
            $company->hax_code = $hax_code;
            $company->rgb_code = hexToRgb($hax_code);
            $company->save();
        }

        echo "Success";
    }

    /**
     *
     */
    public function updateDepartment()
    {

        Storage::makeDirectory('/app/public/1/personalDetails');

        // $getDept = Department::select( 'id', 'hax_code', 'rgb_code' )->get();

        // foreach( $getDept as $dept ){
        //     $hax_code = generateRandomHexColor();
        //     $dept->hax_code = $hax_code;
        //     $dept->rgb_code = hexToRgb( $hax_code );
        //     $dept->save();
        // }

        echo "Success";
    }

    /**
     *
     */
    public function getAdminMenu()
    {
        return getMultiLevelAdminMenuDropdown();
    }

    /**
     *
     */
    public function getIndustries()
    {
        return getIndustries();
    }

    /**
     *
     */
    public function getCompaniesByIndustryID($industry_id)
    {

        if (is_numeric($industry_id)) {
            return getCompanyByIndustryID($industry_id);
        } else {
            try {
                $industry_id = _de($industry_id);

                $companyData = Company::where(['industry_id' => $industry_id, 'status' => 1])->orderBy('name', 'ASC')->get();
                $responseArr = [];
                foreach ($companyData as $k => $data) {
                    $responseArr[$k]['id'] = _en($data->id);
                    $responseArr[$k]['name'] = $data->name;
                }

                $response = [
                    'success' => true,
                    'data'    => $responseArr,
                    'message' => "Retrive data successfully",
                ];

                return response()->json($response, 200);
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'data'    => [],
                    'message' => "Sorry !! You are Unauthorized to Access this company data!",
                ];
                return response()->json($response, 403);
            }
        }
    }

    /**
     *
     */
    public function getDepartmentByCompanyID($company_id)
    {
        if (is_numeric($company_id)) {
            return getDepartmentByCompanyID($company_id);
        } else {
            try {
                $company_id = _de($company_id);

                $companyData = Department::where(['company_id' => $company_id, 'status' => 1])->orderBy('name', 'ASC')->get();
                $responseArr = [];
                foreach ($companyData as $k => $data) {
                    $responseArr[$k]['id'] = _en($data->id);
                    $responseArr[$k]['name'] = $data->name;
                }

                $response = [
                    'success' => true,
                    'data'    => $responseArr,
                    'message' => "Retrive data successfully",
                ];

                return response()->json($response, 200);
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'data'    => [],
                    'message' => "Sorry !! You are Unauthorized to Access this company data!",
                ];
                return response()->json($response, 403);
            }
        }
    }

    /**
     *
     */
    public function getEmployeeNoticeHistory( $employeeId = null )
    {
        $response = [
            'success' => true,
            'data'    => NoticeBoard::where(['employee_id' => $employeeId])->get()->toArray(),
            'message' => "Retrive Employee Notice History",
        ];

        return response()->json($response, 200);
    }

    /**
     *
     */
    public function clonePermission()
    {

        $userRole = Role::where(['name' => 'user', 'guard_name' => 'web'])->first();
        $permission = Permission::where('guard_name', 'user')->get();

        foreach ($permission as $ar) {
            $userRole->givePermissionTo($ar);
        }

        // Assign role to user (web guard)
        $user = User::find(1); // Example user
        $user->assignRole('user');

        echo "Success";
    }

    /**
     *
     */
    public function cloneRolePermission()
    {

        for ($i = 147; $i <= 272; $i++) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $i,
                'role_id' => 2,
            ]);
        }

        echo "Success";
    }

    /**
     *
     */
    public function getReligions()
    {
        return getReligions();
    }

    /**
     *
     */
    public function getShiftDetailList($id)
    {
        return getShiftDetailList($id);
    }

    /**
     *
     */
    public function getHolidayList()
    {
        return getHolidayList();
    }

    /**
     * update client Email id
     */
    public function updateClientEmail()
    {
        $clientObjs = Person::where('type', 3)->select('id', 'unique_id', 'email_id')->get();

        foreach ($clientObjs as $cr) {
            $cr->email_id = $cr->unique_id . "@mailinator.com";
            $cr->save();
        }
    }

    /**
     * send temp mail
     */
    public function sendTempMail()
    {
        $data = [
            'name' => "Gautam Kakadiya",
            'company_name' => 'Devotion Business',
            'register_link' => url('complete-register/' . _en(1)),
        ];

        Mail::to('gk@mailinator.com')->send(new NewEmployeeRegister($data));
    }

    /**
     *
     */
    public function prismAI()
    {
        $prism = Prism::text()
            ->using('openai', 'gpt-4o')
            ->withSystemPrompt(view('prompts.ai'))
            ->withPrompt('Explain quantum computing to a 5-year-old.');

        $response = $prism();

        echo $response->text;
    }

    /**
     *
     */
    public function clearEmployeeDatabaseHistory()
    {
        //get Employee history
        $empObjs = Person::where('type', 1)->get()->pluck('id');
        if ($empObjs) {
            foreach ($empObjs as $id) {
                removeEmployeeHistoryData($id);
            }
        }

        echo "Remove all " . COUNT($empObjs) . " employee datas";
    }

    /**
     *
     */
    public function clearClientDatabaseHistory()
    {
        //get Client history
        $clientObjs = Client::all()->pluck('id');
        if ($clientObjs) {
            foreach ($clientObjs as $id) {
                removeClientHistoryData($id);
            }
        }

        echo "Remove all " . COUNT($clientObjs) . " client datas";
    }

    /**
     *
     */
    public function getLogos()
    {
        return getLogos();
    }

    /**
     *
     */
    public function getQualifications($parent_id = 0)
    {
        return getQualifications($parent_id);
    }

    /**
     *
     */
    public function getCommunicationType()
    {
        return getCommunicationType('API');
    }

    /**
     * get all activity logs
     */
    public function getAdminLogDifferentDetails( $id )
    {
        $logHistoryObj = AdminLog::select('table_view')->find( $id );

        $response = [
            'success' => true,
            'data'    => $logHistoryObj->table_view,
            'message' => "Retrive log different data successfully",
        ];

        return response()->json($response, 200);
    }

    /**
     * Recursively deletes empty folders in the given directory.
     *
     * @param string $directory The directory to check for empty folders.
     */
    function deleteEmptyFolders( $directory = '' )
    {
        // Get all directories within the specified directory
        $directories = Storage::directories($directory);

        // Recursively check and delete empty child folders
        foreach ($directories as $dir) {
            $this->deleteEmptyFolders($dir); // Recursive call for subdirectories
        }

        // After processing subdirectories, check if the current directory is empty
        if ( empty( Storage::files( $directory ) ) && empty( Storage::directories( $directory ) ) ) {
            Storage::deleteDirectory($directory);
            echo "Deleted empty folder: {$directory}\r\n";
        }
    }

    /**
     * Manually Removing One-Day-Old Sessions
     */
    public function removeOldFrameworkSessionFiles()
    {
        $sessionPath = storage_path('framework/sessions');
        $files = File::files($sessionPath);

        $count = 0;
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime());
            if (now()->diffInDays($lastModified) > 1) {
                File::delete($file);

                $count++;
            }
        }

        echo "Remove total session files in framework folder is: " . $count;

        $viewPath = storage_path('framework/views');
        $files = File::files($viewPath);

        $count = 0;
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime());
            if (now()->diffInDays($lastModified) > 5) {
                File::delete($file);

                $count++;
            }
        }

        echo "<br>Remove total vies files in framework folder is: " . $count;
    }

    /**
     *
     */
    public function getEmployeeVisitingCardDetails( $companySlug, $employeeSlug ){

        //get Company Details
        $companyObj = Company::where( 'slug', $companySlug )->first();

        //get Employee Details
        $employeeObj = Portfolio::where( 'slug', $employeeSlug )->first();

        $canonical = request()->url();
        return view( $companySlug.'/index', compact( 'companyObj', 'employeeObj', 'canonical' ) );
    }

    /**
     *
     */
    public function setDubaiBaseDateTime(){

        try{
            $result = getLocationBaseDateTime( "Asia/Dubai" );

            $day = date('d');
            $date = date('d-m-Y');
            if( COUNT( $result ) > 0 ){
                $day = $result['day'];
                $date = $result['day']."/".$result['month']."/".$result['year'];
            }

            Configuration::where(['key' => 'CURRENT_DATE'])->update(['value' => $date] );
            Configuration::where(['key' => 'CURRENT_DAY'])->update(['value' => $day] );

            return true;
        } catch( Exception $e){
            session()->flash('error', "We couldn't complete your request. Please log in again.");
            return false;
        }

        // echo "Set Successfully: ".$date;
    }

    /**
     * Remove old database backup
     */
    public function removeOldBackupDatabase()
    {
        $directory = storage_path('app/backups'); // or a subfolder like storage_path('app/temp')
        $deleted = 0;

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            if (now()->diffInDays($file->getMTime()) > 15) {
                File::delete($file->getRealPath());
                $deleted++;
            }
        }

        echo "Deleted ".$deleted." files older than 15 days.";
    }
}
