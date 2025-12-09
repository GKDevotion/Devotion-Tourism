<?php
/*
* @Function:        <pr>
* @Author:          Gautam Kakadiya
* @Created On:      <07-03-2019>
* @Last Modified By:
* @Last Modified:
* @Description:     <This methode print data in array formate>
*/

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

/*---------------------------------------------
   get secon parameter if you want to die code
 ----------------------------------------------*/
function pr($data , $die = false)
{
    echo "<pre>";
    print_r($data);
}

/*
* @Function:        <unsetData>
* @Author:          Gautam Kakadiya
* @Created On:      <07-03-2019>
* @Last Modified By:
* @Last Modified:
* @Description:     <This methode unsetdata from 'From' request>
*/
function unsetData($dataArray = array(), $unsetDataArray = array())
{
    return array_diff_key($dataArray, array_flip($unsetDataArray));
}


/**
 * @Function:        <formatDate>
 * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
 * @Created On:      <17-10-2021>
 * @Last Modified By:Gautam Kakadiya
 * @Last Modified:   Gautam Kakadiya
 * @Description:     <This function is Converting database format date to convienant form >
 * @params :
 * @date : Date which you get from database.
 * @format : Format you want to retrieve.
 * @return :
 *		- Formatted date.
 */
function formatDate($format = '',$date = '', $isProperFormat=false)
{
    if( $isProperFormat )
    {
        $dateArr = explode("/", $date);

        if( isset( $dateArr[1] ) ){
            if( strlen( $dateArr[0] ) == 4 )
                return $date;
            else
                return Carbon::createFromFormat( 'd/m/Y', $date )->format( 'Y-m-d' );
        }
        else
            return $date;
    }
	else if($format)
		return date($format,strtotime($date));
	else
		return date('Y-m-d H:i:s');
}

/**
 * @Function:        <formatTime>
 * @Author:          Gautam Kakadiya( ShreeGurave Dev Team )
 * @Created On:      <28-10-2021>
 * @Last Modified By:Gautam Kakadiya
 * @Last Modified:   Gautam Kakadiya
 * @Description:     <This function is Converting database format time to convienant form >
 * @params :
 * @date : Date which you get from database.
 * @format : Format you want to retrieve.
 * @return :
 *		- Formatted date.
 */
function formatTime($format = '',$time = '', $isProperFormat=false)
{
    if( $isProperFormat )
    {
        $timeArr = explode(":", $time);
        if( $timeArr[2] == "00" )
            return $time;
        else {
            return date('h:i A', strtotime($time));// DateTime::createFromFormat( 'h:i A', str_ireplace( " ", "", $time ) )->format( 'H:i:s' );
        }
    }
    else if($format)
        return date($format,strtotime($time));
    else
        return date('H:i:s');
}

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with a "separator" string
 * as the word separator.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the separator
 * @return	string
 */
if ( ! function_exists('url_title'))
{
	function url_title($str, $separator = '-', $lowercase = FALSE )
	{
		if ($separator == 'dash')
		{
			$separator = '-';
		}
		else if ($separator == 'underscore')
		{
			$separator = '_';
		}

		$q_separator = preg_quote($separator);

		$trans = array(
				'&.+?;'                 => '',
				'[^a-z0-9 _-]'          => '',
				'\s+'                   => $separator,
				'('.$q_separator.')+'   => $separator
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		return trim($str, $separator);
	}
}
