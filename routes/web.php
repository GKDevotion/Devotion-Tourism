<?php

use App\Http\Controllers\Backend\DownloadController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Mail\ClientOnBoardMail;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "will clear the all cached!";
});

Route::get('file', [FileController::class, 'index'])->name('file');
Route::post('file', [FileController::class, 'store'])->name('file.store');

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Visiting Card
 */
Route::get('visiting-card/{company?}/{employee?}', [CronController::class, 'getEmployeeVisitingCardDetails']);// view employee visiting card details via company base

/**
 * Excel Modules
 */
Route::post('/client-company-download-csv', [DownloadController::class, 'downloadClientCompanyCSV']);
Route::get('/client-company-download-pdf', [DownloadController::class, 'downloadClientCompanyPDF']);
Route::post('/client-company-upload-csv', [DownloadController::class, 'uploadClientCompanyCSVFile']);
Route::post('/account-summery-download-csv', [DownloadController::class, 'downloadAccountSummeryCSVFile']);// download account summery as csv file format
Route::get('/account-summery-download-pdf', [DownloadController::class, 'downloadAccountSummeryPDFFile']);// download account summery as pdf file format
Route::get('/account-summery-view', [DownloadController::class, 'viewAccountSummery']);// view account summery as table format
Route::post('/account-summery-upload-csv', [DownloadController::class, 'uploadAccountSummeryCSVFile']);// upload account summery as csv file format
Route::get('/account-summery-view-pdf', [DownloadController::class, 'viewAccountSummeryPDFFile']);// view account summery as pdf file format

Route::get('send-employee-auth-access-mail', [MailController::class, 'sendEmployeeAuthAccessMail'] );
Route::any('client-schedule-reminder-notes-mail', [MailController::class, 'sendClientReminderNoteNotificationMail'] );
Route::any('leave-notification-mail', [MailController::class, 'sendNewLeaveApplicationMail'] );
Route::get('client-personal-meeting-to-admin-mail', [MailController::class, 'sendPersonalMeetingAdminMail'] );
Route::get('company-meeting-to-admin-mail', [MailController::class, 'sendCompanyMeetingAdminMail'] );

/**
 * Cron/Backup Controller
 */
Route::get('full-database-backup', [BackupController::class, 'getFullDatabaseBackup'] );
Route::get('set-dubai-base-date', [CronController::class, 'setDubaiBaseDateTime'] );
Route::get('remove-old-database-backup', [CronController::class, 'removeOldBackupDatabase'] );

/**
 * Google Console related APIs
 */
Route::get('/create-event', function( GoogleCalendarService $calendarService ){
    return $calendarService->createGoogleCalendarEvent();
});
Route::get('google-calender-auth', [GoogleController::class, 'redirectToGoogleCalenderAuth']);
Route::get('google-calender-callback', [GoogleController::class, 'handleGoogleCalenderCallback']);

/**
 * Temp Routes
 */
Route::get('add-religion', [CronController::class, 'addReligion'] );
Route::get('update-continent', [CronController::class, 'updateContinent'] );
Route::get('update-country', [CronController::class, 'updateCountry'] );
Route::get('update-state', [CronController::class, 'updateState'] );
Route::get('update-city', [CronController::class, 'updateCity'] );
Route::get('update-induxtry-color', [CronController::class, 'updateIndustryHexCode'] );

Route::get('update-company', [CronController::class, 'updateCompany'] );
Route::get('update-department', [CronController::class, 'updateDepartment'] );
Route::get('get-admin-menu', [CronController::class, 'getAdminMenu'] );
Route::get('clone-permission', [CronController::class, 'clonePermission'] );
Route::get('clone-role-permission', [CronController::class, 'cloneRolePermission'] );
Route::get('update-client-email', [CronController::class, 'updateClientEmail'] );
Route::get('send-tmp-mail', [CronController::class, 'sendTempMail'] );
Route::get('prism-ai', [CronController::class, 'prismAI'] );

Route::get( 'send-client-on-board-mail', function(){
    $data = [
        'name' => 'Gautam Kakadiya',
        'subject' => 'Welcome to Our Devotion Group!'
    ];

    Mail::to( "gk@mailinator.com" )->send( new ClientOnBoardMail( $data ) );
} );

/**
 * http://5.30.98.68:6443/devotionreport/update-account-summery?paymentType=0&company_id=15
 * UPDATE `account_summeries` SET `is_check_balance`=0 WHERE `company_id` = 11 AND `payment_type` = 0 AND `date` >= '2025-01-03'
 */
Route::get( 'update-account-summery', function( Request $request ){
    $paymentType = $request->paymentType ?? null;
    updateAccountSummeryBalance( $request->company_id, $paymentType, true );
} );

/**
 * get Temp Company
 */
Route::get('get-devotion-companies', [HomeController::class, 'getCompanies'] );
Route::get('get-devotion-company-base-client', [HomeController::class, 'getCompanBaseClient'] );
Route::get('get-devotion-company-base-bank-accounts', [HomeController::class, 'getCompanBaseBankAccount'] );
Route::get('get-devotion-company-base-account-summery', [HomeController::class, 'getCompanBaseAccountSummery'] );
Route::get('get-account-summery-documents', [HomeController::class, 'getAccountSummeryDocuments'] );
Route::get('update-account-summery-admin', [HomeController::class, 'updateAccountSummeryAdmin'] );
Route::get('update-bank-name-to-slug', [HomeController::class, 'updateBankNameToSlug'] );
Route::get('/account-summery-download-csv', [DownloadController::class, 'downloadAccountSummeryCSVFile']);
