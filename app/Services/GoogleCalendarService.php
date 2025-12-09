<?php

namespace App\Services;

use Google_Client;
use Illuminate\Support\Facades\Storage;
use Exception;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

class GoogleCalendarService
{
    public function getClient()
    {
        $client = new Google_Client();
        $client->setClientId( env( 'GOOGLE_CALENDER_CLIENT_ID' ) );
        $client->setClientSecret( env( 'GOOGLE_CALENDER_CLIENT_SECRET' ) );
        $client->setRedirectUri( url( 'google-calender-callback' ) );

        // Load saved tokens
        $tokenPath = Storage::path('credentials/google-token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);

            // Refresh the token if expired
            if ($client->isAccessTokenExpired()) {
                $refreshToken = $client->getRefreshToken();
                $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                $client->setAccessToken($newToken);

                // Save the updated token
                Storage::put('credentials/google-token.json', json_encode($newToken));
            }
        }

        return $client;
    }

    function createGoogleCalendarEvent()
    {
        try{
            // Initialize Google Client
            // $client = new Client();
            // $client->setAuthConfig(storage_path('credentials/google-calendar.json'));
            // $client->addScope(Calendar::CALENDAR);
            $client = $this->getClient();
            
            $service = new Calendar($client);
            $dayBeforeReminder = 24 * 60 * 1; // Reminder 1 day before
            $minuteBeforeReminder = 10; // Reminder 10 minutes before

            // Set the event details
            $event = new Event([
                'summary' => 'Laravel Test Event',
                'location' => null,
                'description' => 'This is a test event created from Laravel',
                'start' => new EventDateTime([
                    'dateTime' => '2025-01-03T10:00:00-07:00', // Replace with your desired start date/time
                    'timeZone' => env('APP_TIMEZONE'),
                ]),
                'end' => new EventDateTime([
                    'dateTime' => '2025-01-03T11:00:00-07:00', // Replace with your desired end date/time
                    'timeZone' => env('APP_TIMEZONE'),
                ]),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => $dayBeforeReminder], // Reminder 1 day before
                        ['method' => 'popup', 'minutes' => $minuteBeforeReminder], // Reminder 10 minutes before
                    ],
                ],
            ]);

            // Insert the event into Google Calendar
            // $calendarId = env('GOOGLE_CALENDER_CLIENT_ID');
            $createdEvent = $service->events->insert('primary', $event);//$service->events->insert($calendarId, $event);

            return response()->json([
                'type' => 'success',
                'message' => 'Event created successfully',
                'event' => $createdEvent,
                'success' => true
            ]);
        } catch ( Exception $e ){
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
                'event' => null,
                'success' => false
            ]);
        }
    }
}
