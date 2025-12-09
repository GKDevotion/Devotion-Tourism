<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContinentController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');

    return response()->json(
        [
            'success' => true,
            'data'    => [],
            'message' => "Will clear the all cached!",
        ],
        200
    );
});

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {

    /**
     * To implement a password change with email OTP confirmation
     */
    Route::post('send-otp', [PasswordController::class, 'sendOtpAPI']);
    Route::post('change-password', [PasswordController::class, 'changePasswordAPI']);
});

Route::get('get-continent-list', [CronController::class, 'getContinent'] );
Route::get('get-country-list/{id?}', [ContinentController::class, 'getCountryByContinentID'] );
Route::get('get-state-list/{id?}', [ContinentController::class, 'getStateByCountryID'] );
Route::get('get-states', [ContinentController::class, 'getStates'] );
Route::get('get-city-list/{id?}', [ContinentController::class, 'getCityByStateByID'] );
Route::get('get-cities', [ContinentController::class, 'getCities'] );
Route::get('get-social-media-platform', [CronController::class, 'getSocialMediaPlatform'] );
Route::get('get-employees-notice-history/{id}', [CronController::class, 'getEmployeeNoticeHistory'] );

Route::get('get-industry-list', [CronController::class, 'getIndustries'] );
Route::get('get-religion-list', [ContinentController::class, 'getReligions'] );
Route::get('get-company-list/{id}', [CronController::class, 'getCompaniesByIndustryID'] );
Route::get('get-department-list/{id}', [CronController::class, 'getDepartmentByCompanyID'] );
Route::get('get-shift-detail/{id}', [CronController::class, 'getShiftDetailList'] );
Route::get('logos', [CronController::class, 'getLogos'] );
Route::get('get-qualification-list', [CronController::class, 'getQualifications'] );
Route::get('get-communication-type', [CronController::class, 'getCommunicationType'] );

Route::get('get-admin-log-different-details/{id?}', [CronController::class, 'getAdminLogDifferentDetails']);
