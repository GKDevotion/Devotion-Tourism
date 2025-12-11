<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\WebsitesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Backend\AdminLogController;
use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\StatesController;
use App\Http\Controllers\Backend\UsersController;

// Prefix all routes with 'admin' and add middleware if needed
Route::prefix('admin')->group(function () {

    // Login Routes
    Route::get('login', 'Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login/submit', 'Backend\Auth\LoginController@login')->name('admin.login.submit');

    // Logout Routes
    Route::post('logout/submit', 'Backend\Auth\LoginController@logout')->name('admin.logout.submit');

    // Forget Password Routes
    Route::get('/password/reset', 'Backend\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset/submit', 'Backend\Auth\ForgetPasswordController@reset')->name('admin.password.update');

    // Dashboard Routes
    Route::get('/', 'Backend\DashboardController@index')->name('admin.dashboard.index');

    /**
     * DG Management
     */
    Route::resource('companies', 'Backend\CompanyController', ['names' => 'admin.company']);
    Route::get('/company-ajax-data', [CompanyController::class, 'ajaxIndex'])->name('company.ajaxIndex');
    Route::get('/company-account-field-mapping/{id}', [CompanyController::class, 'accountingMapping'])->name('admin.company-account-field-map.update');
    Route::post('/company-account-field-mapping', [CompanyController::class, 'storeAccountingMapping'])->name('admin.company-account-field-map.store');
    Route::get('/company-account-field-indexing/{id}', [CompanyController::class, 'accountingIndexing'])->name('admin.company-account-field-index');
    Route::post('/company-account-field-indexing', [CompanyController::class, 'storeAccountingIndexing'])->name('admin.company-account-field-indexing.store');

    Route::resource('locations', 'Backend\LocationController', ['names' => 'admin.locations']);
    Route::get('/locations-ajax-data', [LocationController::class, 'ajaxIndex'])->name('locations.ajaxIndex');

    Route::resource('currency', 'Backend\CurrencyController', ['names' => 'admin.currency']);

    /**
     * User Management
     */

    Route::resource('users', 'Backend\UsersController', ['names' => 'admin.user']);
    Route::get('/user-ajax-data', [UsersController::class, 'ajaxIndex'])->name('user.ajaxIndex');

    Route::resource('admins', 'Backend\AdminsController', ['names' => 'admin.admin']);

    /**
     * Continent Management
     */
    Route::resource('religions', 'Backend\ReligionsController', ['names' => 'admin.religion']);
    Route::resource('continents', 'Backend\ContinentsController', ['names' => 'admin.continent']);
    Route::resource('countries', 'Backend\CountriesController', ['names' => 'admin.country']);
    Route::resource('states', 'Backend\StatesController', ['names' => 'admin.state']);
    Route::get('/state-ajax-data', [StatesController::class, 'ajaxIndex'])->name('state.ajaxIndex');

    /**
     * Setting
     */
    Route::resource('menu', 'Backend\MenuController', ['names' => 'admin.menu']);
    Route::resource('logs', 'Backend\AdminLogController', ['names' => 'admin.admin-log']);
    Route::get('/admin-log-ajax-data', [AdminLogController::class, 'ajaxIndex'])->name('admin-log.ajaxIndex');
    Route::resource('roles', 'Backend\RolesController', ['names' => 'admin.role']);
    Route::resource('permission', 'Backend\PermissionController', ['names' => 'admin.permission']);
    Route::post('changePermission', [PermissionController::class, 'changePermission']);
    Route::get('change-password', [AdminsController::class, 'changePassword'])->name('admin.change-password');
    Route::resource('configurations', 'Backend\ConfigurationController', ['names' => 'admin.configurations']);

        Route::resource('website', 'Admin\WebsitesController', ['names' => 'admin.website']);
    Route::get('/website-ajax-data', [WebsitesController::class, 'ajaxIndex'])->name('website.ajaxIndex');

    // Route::group(['prefix' => 'website'], function () {
    //     Route::get('/', [WebsitesController::class, 'index'])->name('admin.website.index');
    //     Route::get('/create', [WebsitesController::class, 'create'])->name('admin.website.create');
    //     Route::post('/store', [WebsitesController::class, 'store'])->name('admin.website.store');
    //     Route::get('/edit/{id}', [WebsitesController::class, 'edit'])->name('admin.website.edit');
    //     Route::post('/update/{id}', [WebsitesController::class, 'update'])->name('admin.website.update');
    //     Route::delete('/delete/{id}', [WebsitesController::class, 'destroy'])->name('admin.website.delete');
    // });

    /**
     * Common Function Routes
     */
    Route::get('update-status/{table}/{id}/{status}', [AdminsController::class, 'updateFieldStatus']);
    Route::get('update-field-status/{table}/{id}/{status}/{field}', [AdminsController::class, 'updateFieldStatus']);
    Route::get('crm-update/{table}/{id}/{crm}', [AdminsController::class, 'updateFieldCRM']);
    Route::get('delete-event/{title}/{id}', [AdminsController::class, 'deleteEvent']);


    /**
     * To implement a password change with email OTP confirmation
     */
    Route::post('/send-otp', [PasswordController::class, 'sendOtp'])->name('send.otp');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('change.password');
});
