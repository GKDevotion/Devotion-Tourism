<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Cron/Backup Controller
 */
Route::get('full-database-backup', [BackupController::class, 'getFullDatabaseBackup'] );
Route::get('set-dubai-base-date', [CronController::class, 'setDubaiBaseDateTime'] );
Route::get('remove-old-database-backup', [CronController::class, 'removeOldBackupDatabase'] );
