<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\BackupController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CronController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_DASHBOARD;

    /**
     * show login form for admin guard
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }


    /**
     * login admin
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        // Validate Login Data
        $request->validate([
            'email' => 'required|max:50',
            'password' => 'required',
        ]);

        $redirection = null;
        $logArr = [];
        // Attempt to login
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

            $admin = Auth::guard('admin')->user();
            if( $admin->status ){
                session()->flash('success', 'Successully Logged in !');
                $logArr['description'] = "Login with Email username is: ".Auth::guard('admin')->user()->username;
                $redirection = 'admin.dashboard.index';
            } else {
                session()->flash('error', 'Your account has been disabled. Please contact the administrator for assistance.');
                return back();
            }
            // return redirect()->route('admin.dashboard.index');// Redirect to dashboard
        } else {
            // Search using username
            if (Auth::guard('admin')->attempt(['username' => $request->email, 'password' => $request->password], $request->remember)) {

                $admin = Auth::guard('admin')->user();
                if( $admin->status ){
                    session()->flash('success', 'Successully Logged in !');
                    $logArr['description'] = "Login with Username: ".Auth::guard('admin')->user()->username;
                    $redirection = 'admin.dashboard.index';
                } else {
                    session()->flash('error', 'Your account has been disabled. Please contact the administrator for assistance.');
                    return back();
                }
                // return redirect()->route('admin.dashboard.index');
            }

            // error
            session()->flash('error', 'Invalid username or password');
            // return back();
        }

        if( !$redirection ){
            return back();
        } else {

            $setTime = new CronController();
            $updateTime = $setTime->setDubaiBaseDateTime();

            // $backup = new BackupController();
            // $updateBKP = $backup->getFullDatabaseBackup();

            if( $updateTime ) {//&&  $updateBKP ){

                $admin = Auth::guard('admin')->user();
                // Store the current session ID
                $admin->session_id = Session::getId();
                $admin->save();

                $logArr['admin_id'] = Auth::guard('admin')->user()->id;
                $logArr['ip_address'] = $request->ip();
                $logArr['action'] = "L";
                saveAdminLog( $logArr );// Save Access log history

                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('config:cache');

                return redirect()->route( $redirection );

            } else {
                // return redirect()->route( 'admin.login' );
                return back();
            }
        }

    }

    /**
     * logout admin guard
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $logArr = [];
        $logArr['admin_id'] = Auth::guard('admin')->user()->id;
        $logArr['ip_address'] = $request->ip();
        $logArr['action'] = "L";
        $logArr['description'] = "Logout User: ".Auth::guard('admin')->user()->username;
        saveAdminLog( $logArr );// Save Access log history

        Cache::forget('user_session_' . Auth::guard('admin')->user()->id );

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();



        return redirect()->route('admin.login');
    }
}
