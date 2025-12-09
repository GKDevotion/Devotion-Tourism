<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Permission;
use App\Models\State;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

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
    public function getAdminMenu()
    {
        return getMultiLevelAdminMenuDropdown();
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
    public function getLogos()
    {
        return getLogos();
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
