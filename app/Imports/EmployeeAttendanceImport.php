<?php

namespace App\Imports;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeAttendanceImport implements ToModel, WithHeadingRow
{
    protected $person_id;
    protected $user;

    // Constructor to accept additional variables
    public function __construct( int $person_id, $user )
    {
        $this->person_id = $person_id;
        $this->user = $user;
    }

    /**
     * 
     */
    public function model(array $row)
    {
        $in_time = Carbon::parse( convertCarbonDateFormat( $row['in_time'] ) );
        // $out_time = Carbon::parse( $this->convertCarbonDateFormat( $row['out_time'] ) );

        // Extract only the date in 'Y-m-d' format
        $date = $in_time->format('Y-m-d');

        // // Difference in hours
        // $diffInHours = $in_time->diffInHours($out_time);

        // // Difference in minutes
        // $diffInMinutes = $in_time->diffInMinutes($out_time);

        // // Difference in seconds
        // $diffInSeconds = $in_time->diffInSeconds($out_time);

        // $diff = $in_time->diff($out_time);

        //Assume you have a Attendance model and want to either update or create a employee based on their attendance data.
        Attendance::updateOrCreate( [
            'admin_id' => $this->user->id,
            'user_id' => $this->user->id,
            'person_id' => $this->person_id,
            'date' => $date,
            'time_in' => $row['in_time'],
        ],[
            'time_out' => $row['out_time'],
            'total_minute' => $row['total_minute']
        ] );
    }
}