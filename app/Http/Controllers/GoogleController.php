<?php

namespace App\Http\Controllers;

use Google\Service\Calendar;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleController extends Controller
{
    private $GOOGLE_CALENDER_CLIENT_ID = "";
    private $GOOGLE_CALENDER_CLIENT_SECRET = "";
    private $GOOGLE_CALENDER_CALLBACK = "";

    public function __construct()
    {
        $this->GOOGLE_CALENDER_CLIENT_ID = "1055880784915-gfcpin9tducoga5bjo65cqqdh4o0jdtn.apps.googleusercontent.com";
        $this->GOOGLE_CALENDER_CLIENT_SECRET = "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";
        $this->GOOGLE_CALENDER_CALLBACK = "google-calender-callback";
    }

    public function redirectToGoogleCalenderAuth()
    {
        $client = new Google_Client();
        $client->setClientId( $this->GOOGLE_CALENDER_CLIENT_ID );
        $client->setClientSecret( $this->GOOGLE_CALENDER_CLIENT_SECRET );
        $client->setRedirectUri( url( $this->GOOGLE_CALENDER_CALLBACK ) );
        $client->addScope(Calendar::CALENDAR);

        return redirect( $client->createAuthUrl() );
    }

    public function handleGoogleCalenderCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId( $this->GOOGLE_CALENDER_CLIENT_ID );
        $client->setClientSecret( $this->GOOGLE_CALENDER_CLIENT_SECRET );
        $client->setRedirectUri( url( $this->GOOGLE_CALENDER_CALLBACK ) );

        $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));

        // Save tokens to a secure location
        Storage::put('credentials/google-token.json', json_encode($token));

        return redirect('/')->with('success', 'Google Calendar connected successfully!');
    }

}