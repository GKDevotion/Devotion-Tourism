<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ChangePasswordOTPMail;
use App\Models\Admin;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PasswordController extends Controller
{
    // Send OTP to the user's email
    public function sendOtp(Request $request)
    {
        $email = Auth::guard('admin')->user()->email;
        try{
            $request->validate([
                'email' => 'required|email|exists:admins,email',
            ]);

            $otp = rand(100000, 999999); // Generate a 6-digit OTP
            Session::put('otp', $otp);
            Session::put('otp_email', $email );

            $data = [
                'name' => Auth::guard('admin')->user()->username,
                'otp' => $otp,
                'subject' => Auth::guard('admin')->user()->username.' - Your OTP to Change Password'
            ];

            // Send OTP via email
            if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                Mail::to( $email )->send( new ChangePasswordOTPMail( $data ) );
                // Mail::raw("Your OTP for password change is: $otp", function ($message) use ($email) {
                //     $message->to( $email )
                //             ->subject('Your OTP to Change Password');
                // });
            }

            return response()->json(['message' => "OTP sent successfully! It may take a few seconds to arrive."]);
        } catch( Exception $e ){
            $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $email." ".$e->getMessage(). PHP_EOL;
            Storage::append("changePass/".date('d-m-Y').".log", $message );

            return response()->json(['message' => $e->getMessage()]);
        }
    }

    // Change the user's password
    public function changePassword(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $email = Auth::guard('admin')->user()->email;
        if ( $email !== Session::get('otp_email')) {
            return response()->json(['message' => 'Email does not match.'], 422);
        }

        if ($request->otp != Session::get('otp')) {
            return response()->json(['message' => 'Invalid OTP.'], 422);
        }

        // Update the password
        $user = Admin::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear the OTP session
        Session::forget('otp');
        Session::forget('otp_email');

        return response()->json(['message' => 'Password changed successfully.']);
    }

    public function sendOtpAPI(Request $request)
    {
        $email = $request->email;
        try{
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $otp = rand(100000, 999999); // Generate a 6-digit OTP

            $userInfo = User::where( 'email', $request->email )->first();
            $userInfo->otp = $otp;
            $userInfo->otp_expires_at = now()->addMinutes(10);
            $userInfo->save();

            // // Send OTP via email
            // Mail::raw("Your OTP for password change is: $otp", function ($message) use ($request) {
            //     $message->to($request->email)->subject('Password Change OTP');
            // });

            $data = [
                'name' => $userInfo->name,
                'otp' => $otp,
                'subject' => $userInfo->name.' - Your OTP to Change Password'
            ];

            // Send OTP via email
            if( getConfigurationfield( "IS_SEND_MAIL" ) ){
                Mail::to( $email )->send( new ChangePasswordOTPMail( $data ) );
                // Mail::raw("Your OTP for password change is: $otp", function ($message) use ($email) {
                //     $message->to( $email )
                //             ->subject('Your OTP to Change Password');
                // });
            }

            return response()->json(['message' => "OTP sent successfully! It may take a few seconds to arrive.", 'success' => true ]);
        } catch( Exception $e ){
            $message = "[" . date( 'Y-m-d h:i:s' ) . "]". $email." ".$e->getMessage(). PHP_EOL;
            Storage::append("changePass/".date('d-m-Y').".log", $message);

            return response()->json(['message' => $e->getMessage(), 'success' => false]);
        }
    }

    public function changePasswordAPI(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp != $request->otp ){// || now()->isAfter($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP.', 'success' => false], 422);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null; // Clear OTP
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Password changed successfully.', 'success' => true ]);
    }
}