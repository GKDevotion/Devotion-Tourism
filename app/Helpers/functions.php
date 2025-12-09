<?php
/*
* @Function:        <pr>
* @Author:          Gautam Kakadiya
* @Created On:      <07-03-2019>
* @Last Modified By:
* @Last Modified:
* @Description:     <This methode print data in array formate>
*/

use App\Models\Accounting;
use App\Models\AccountManagememt;
use App\Models\BankInformation;
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


/**
 * Undocumented function
 *
 * @param [type] $companyId
 * @param integer $no
 * @return void
 */
function getAccountSummeryUniqueId( $companyId=0, $no=5 ){
    // $accountingSummeryCountObj = Accounting::select('id')->WHERE( ['company_id' => $companyId] );
    // return str_pad( ( $accountingSummeryCountObj->count() + 1 ), $no, '0', STR_PAD_LEFT );
    $accountingSummeryCountObj = Accounting::select(DB::raw('MAX(CAST(SUBSTRING(txn_no, 4) AS UNSIGNED)) as txn_no'))->first();
    return "TRN".$accountingSummeryCountObj->txn_no + 1;
}

/**
 * Undocumented function
 *
 * @param [type] $companyId
 * @param integer $no
 * @return void
 */
function appendClientCompanyUniqueId( $companyId=0, $no=4 ){
    $companyObj = Company::select( 'sort_name' )->find( $companyId );
    $accountingSummeryCountObj = AccountManagememt::select('id')->WHERE( ['company_id' => $companyId] );
    return $companyObj->sort_name.str_pad( ( $accountingSummeryCountObj->count() + 1 ), $no, '0', STR_PAD_LEFT );
}

/**
 * To recursively fetch and update one record at a time in Laravel until no more records are left, you can use a while loop.
 */
function updateAccountSummeryBalance( $companyId, $paymentType = null, $showDebug = false ){

    //cross check payment type available or manage selected bank payment method
    if( $paymentType != null ){
        $bankArr = [$paymentType];
    } else {
        //get Company Base Bank Account Details
        $bankObj = BankInformation::where([
            'company_id' => $companyId,
            'status' => 1
        ])
        ->pluck('id')
        ->toArray();
        $bankArr = array_merge( [0], $bankObj );
    }

    //while available any bank method then update all account summery balance once is_check_balance = 0
    foreach( $bankArr as $payment_type ){
        while (true) {

            if( false ){
                $accountObjs = Accounting::where([
                    'is_check_balance' => 0,
                    'company_id' => $companyId,
                    'payment_type' => $payment_type
                ])
                ->select('id', 'debit_amount', 'credit_amount', 'balance', 'is_check_balance')
                ->orderBy('id', 'asc')
                ->first();

                if (!$accountObjs) break;

                $accountBalanceObj = Accounting::where([
                    'is_check_balance' => 1,
                    'company_id' => $companyId,
                    'payment_type' => $payment_type
                ])
                ->select('credit_amount', 'debit_amount', 'balance')
                ->orderBy('id', 'desc')
                ->first();

                $oldBalance = $credit = $debit = 0;
                if ($accountBalanceObj) {

                    if( $accountBalanceObj->balance > 0 ){
                        $oldBalance = $accountBalanceObj->balance;
                        $credit = (float) $accountObjs->credit_amount;
                        $debit  = (float) $accountObjs->debit_amount;
                    } else {
                        $credit = (float) $accountObjs->credit_amount;
                        $debit  = (float) $accountObjs->debit_amount;
                    }
                    // $oldBalance = ($accountBalanceObj->balance > 0)
                    //     ? $accountBalanceObj->balance
                    //     : ( ( $accountBalanceObj->credit_amount > 0 )
                    //         ? $accountBalanceObj->credit_amount
                    //         : -$accountBalanceObj->debit_amount );
                    //     // : max($accountBalanceObj->credit_amount, $accountBalanceObj->debit_amount);

                    // $oldBalance = $accountBalanceObj->balance;


                    $updateBalance = $oldBalance + $credit - $debit;
                } else {

                    $updateBalance = $oldBalance;
                    if ($credit > 0) {
                        $updateBalance += $credit;
                    }

                    if ($debit > 0) {
                        $updateBalance -= $debit;
                        // $updateBalance -= -$debit;
                    }
                }


                // $updateBalance = $oldBalance;
                // if ($credit > 0) {
                //     $updateBalance += $credit;
                // }

                // if ($debit > 0) {
                //     $updateBalance -= $debit;
                //     // $updateBalance -= -$debit;
                // }

                // echo "updateBalance: ".$updateBalance."<br>";
                $accountUpdateObjs = Accounting::find($accountObjs->id);
                $accountUpdateObjs->balance = round($updateBalance, 2);
                $accountUpdateObjs->is_check_balance = 1;
                $accountUpdateObjs->save();
            } else {
                $accountObjs = Accounting::where([
                    'is_check_balance' => 0,
                    'company_id' => $companyId,
                    'payment_type' => $payment_type
                ])
                ->select('id', 'debit_amount', 'credit_amount', 'balance', 'is_check_balance')
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->first();

                if (!$accountObjs) break;

                $accountBalanceObj = Accounting::where([
                    'is_check_balance' => 1,
                    'company_id' => $companyId,
                    'payment_type' => $payment_type
                ])
                ->select('balance')
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

                $oldBalance = $accountBalanceObj ? (float) $accountBalanceObj->balance : 0.00;

                $credit = (float) $accountObjs->credit_amount;
                $debit  = (float) $accountObjs->debit_amount;

                $updateBalance = $oldBalance + $credit - $debit;

                $accountUpdateObjs = Accounting::find($accountObjs->id);
                $accountUpdateObjs->balance = round($updateBalance, 2);
                $accountUpdateObjs->is_check_balance = 1;
                $accountUpdateObjs->save();
            }
        }
    }

    //re calculate company credit, debit and total balance
    $totals = DB::table('account_summeries')
        ->where( 'company_id', $companyId )
        ->selectRaw('SUM(credit_amount) as total_credit, SUM(debit_amount) as total_debit')
        ->first();

    $totalCredit = $totals->total_credit;
    $totalDebit = $totals->total_debit;
    $totalBalance = number_format( $totalCredit - $totalDebit, 2 );

    $updateCompanyData = [
        'total_credit' => $totalCredit,
        'total_debit' => $totalDebit,
        'balance' => $totalBalance,
    ];

    Company::where('id', $companyId)->update( $updateCompanyData );

    if( $showDebug ){
        echo "<pre>";
        print_r( $updateCompanyData );
        echo "</pre>";
    }
}

/**
 * to recursively fetch the last selected (or valid) date from a database (e.g., in case the latest record by date is
 * based on some logic or relationship), you typically don’t need actual recursion, but you can simulate it using a
 * loop or recursive function based on conditions.
 */
function getLastValidDate($date = null)
{
    // If no date is passed, start with today
    $date = $date ? Carbon::parse($date) : now();

    // Try to get a record with this date
    $record = Accounting::whereDate('date', $date->toDateString())->first();

    if ($record) {
        // If record found, return the date
        return $record->date;
    } else {
        // If not found, go back one day and try again (recursive)
        $previousDate = $date->subDay();
        return getLastValidDate($previousDate);
    }
}
