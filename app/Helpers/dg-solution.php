<?php

use App\Models\AdminMenu;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Continent;
use App\Models\Country;
use App\Models\State;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

/**
 * Undocumented function
 *
 * @param [type] $key
 * @return void
 */
function getConfigurationfield($key) {
     $result = Configuration::select('value')->where('key', $key)->first();
     if( $result ) {
         return $result->value;
     } else {
         return false;
     }
}

/**
 * @Function:        <getAdminSideMenu>
 * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
 * @Created On:      <24-11-2021>
 * @Last Modified By:Gautam Kakadiya
 * @Last Modified:   Gautam Kakadiya
 * @Description:     <This function work for get admin panel side bar menu.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
function getAdminSideMenu(){
    $parentArr = AdminMenu::select('id', 'name', 'slug', 'parent_id', 'group_name', 'class_name', 'icon' )
        ->where( ['parent_id' => 0, 'status' => 1 ] )
        ->orderBy( 'sort_order', 'ASC' )
        ->get();

    if( COUNT( $parentArr ) >0 ){
        foreach( $parentArr as $k=>$parent ){
            $parentArr[$k]['childArr'] = AdminMenu::select('id', 'name', 'parent_id', 'group_name', 'class_name', 'icon')->
                where( [
                'parent_id' => $parent->id,
                'status' => 1
            ] )
            ->orderBy( 'sort_order', 'ASC' )
            ->get();
        }
    }

    return $parentArr;
}

/**
 *
 */
if ( !function_exists('format_number_in_k_notation') ) {
    function format_number_in_k_notation(int $number): string
    {
        $suffixByNumber = function () use ($number) {
            if ($number < 1000) {
                return sprintf('%d', $number);
            }

            if ($number < 1000000) {
                return sprintf('%d%s', floor($number / 1000), 'K+');
            }

            if ($number >= 1000000 && $number < 1000000000) {
                return sprintf('%d%s', floor($number / 1000000), 'M+');
            }

            if ($number >= 1000000000 && $number < 1000000000000) {
                return sprintf('%d%s', floor($number / 1000000000), 'B+');
            }

            return sprintf('%d%s', floor($number / 1000000000000), 'T+');
        };

        return $suffixByNumber();
    }
}

/**
 * To generate a random hex color code in PHP, you can use the following function:
 */
function generateRandomHexColor() {
    $randomColor = '';
    for ($i = 0; $i < 6; $i++) {
        $randomColor .= dechex(rand(0, 15));
    }
    return $randomColor;
}

/**
 * To update or convert a hex color code to its RGB equivalent in PHP, you can use the following function:
 */
function hexToRgb( $hex ) {
    // Remove the leading # if it is present
    $hex = ltrim($hex, '#');

    // Convert 3-digit hex to 6-digit hex
    if (strlen($hex) == 3) {
        $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
    }

    // Split the hex code into its components
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return $r.", ".$g.", ".$b;
}

/**
 *
 */
function getMultiLevelAdminMenuDropdown( $parent = 0, $menuArr = [ '0' => '-- Select Parent Menu --' ], $i = -1 )
{
	$res = AdminMenu::select('id', 'name')->where( ['parent_id' => $parent, 'status' => 1 ] )->orderBy( 'sort_order' )->get()->toArray();

	if( count( $res ) > 0 )
	{
		$i++;
		foreach( $res as $r ){
            $menuArr[$r['id']] = str_repeat(' - ',$i).$r['name'];
			$menuArr = getMultiLevelAdminMenuDropdown( $r['id'], $menuArr, $i );
		}
		return $menuArr;
	} else {
		return $menuArr;
    }
}

/**
 *
 */
function getIdBaseValue( $table, $where, $select ){
    $result = DB::table($table)->where( $where )->first();
    return $result->$select;
}

/**
 * "year" => 2025
 * "month" => 5
 * "day" => 23
 * "hour" => 12
 * "minute" => 57
 * "seconds" => 22
 * "milliSeconds" => 204
 * "dateTime" => "2025-05-23T12:57:22.2044151"
 * "date" => "05/23/2025"
 * "time" => "12:57"
 * "timeZone" => "Asia/Kolkata"
 * "dayOfWeek" => "Friday"
 * "dstActive" => false
 */

function getLocationBaseDateTime( $timeZone="Asia/Dubai" ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://timeapi.io/api/Time/current/zone?timeZone=".$timeZone);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // Return the response as a string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   // Follow redirects

    curl_setopt($ch, CURLOPT_ENCODING, '' );
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_TIMEOUT, 0 );
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);

    curl_close($ch);
    if (curl_errno($ch)) {
        return ['day' => date('d'), 'date' => date('d/m/Y')];
    } else {
        return json_decode($response, true);
    }

}
