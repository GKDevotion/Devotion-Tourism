<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Backend\AccountFieldController;
use App\Http\Controllers\Backend\AccountingController;
use App\Http\Controllers\Backend\AccountManagementController;
use App\Http\Controllers\Backend\AdminLogController;
use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\BankInformationController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\StatesController;
use App\Http\Controllers\Backend\UsersController;

// Route::group(['prefix' => 'admin'], function () {

// Route::middleware(['auth', 'check.session'])->group(function () {

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

            Route::resource('departments', 'Backend\DepartmentController', ['names' => 'admin.department']);
            Route::get('/department-ajax-data', [DepartmentController::class, 'ajaxIndex'])->name('department.ajaxIndex');

            Route::resource('locations', 'Backend\LocationController', ['names' => 'admin.locations']);
            Route::get('/locations-ajax-data', [LocationController::class, 'ajaxIndex'])->name('locations.ajaxIndex');

            Route::resource('currency', 'Backend\CurrencyController', ['names' => 'admin.currency']);

            Route::resource('accounting', 'Backend\AccountingController', ['names' => 'admin.accounting']);
            Route::get('/accounting/{id}/edit', [AccountingController::class, 'edit'])->name('admin.accounting.edit');
            Route::get('/accounting-ajax-data', [AccountingController::class, 'ajaxIndex'])->name('accouting.ajaxIndex');
            // Route::delete('/account_summeries', [AccountingController::class, 'destroy'])->name('accouting.ajaxIndex');
            Route::get('/company-account-summery/{id}/{currentPage?}', [AccountingController::class, 'index'])->name('company-account-summery-index');
            Route::get('/create-company-account-summery/{id}', [AccountingController::class, 'create'])->name('create-company-account-summery');

            Route::resource('account-management-fields', 'Backend\AccountFieldController', ['names' => 'admin.account-field']);
            Route::get('/account-management-field-ajax-data', [AccountFieldController::class, 'ajaxIndex'])->name('account-field.ajaxIndex');

            Route::resource('account-managements', 'Backend\AccountManagementController', ['names' => 'admin.account-management']);
            Route::get('/account-managements-ajax-data', [AccountManagementController::class, 'ajaxIndex'])->name('company-account-management.ajaxIndex');
            Route::get('/company-account-managements/{id}', [AccountManagementController::class, 'index'])->name('company-account-management-index');
            Route::get('/create-company-account-management/{id}', [AccountManagementController::class, 'create'])->name('company-account-management-create');
            Route::get('/edit-company-account-management/{id}', [AccountManagementController::class, 'edit'])->name('company-account-management-edit');

            Route::resource('bank-information', 'Backend\BankInformationController', ['names' => 'admin.bank-information']);
            Route::get('/bank-information-ajax-data', [BankInformationController::class, 'ajaxIndex'])->name('bank-information.ajaxIndex');
            Route::get('/company-bank-information/{id}', [BankInformationController::class, 'index'])->name('company-bank-information-index');
            Route::get('/create-company-bank-information/{id}', [BankInformationController::class, 'create'])->name('company-bank-information-create');
            Route::get('/edit-company-bank-information/{id}', [BankInformationController::class, 'edit'])->name('company-bank-information-edit');

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
            // Route::resource('cities', 'Backend\CitiesController', ['names' => 'admin.city']);
            // Route::get('/city-ajax-data', [CitiesController::class, 'ajaxIndex'])->name('city.ajaxIndex');

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

        /**
         * Common Function Routes
         */
            Route::get('update-status/{table}/{id}/{status}', [AdminsController::class, 'updateFieldStatus']);
            Route::get('update-field-status/{table}/{id}/{status}/{field}', [AdminsController::class, 'updateFieldStatus']);
            Route::get('crm-update/{table}/{id}/{crm}', [AdminsController::class, 'updateFieldCRM']);
            Route::get('delete-event/{title}/{id}', [AdminsController::class, 'deleteEvent']);


        Route::resource('inquiry', 'Backend\InquiryController', ['names' => 'admin.inquiry']);
        Route::resource('device-records', 'Backend\DeviceRecordController', ['names' => 'admin.device-record']);

        /**
         * To implement a password change with email OTP confirmation
         */
        Route::post('/send-otp', [PasswordController::class, 'sendOtp'])->name('send.otp');
        Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('change.password');

    });

// });

?>
