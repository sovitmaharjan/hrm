<?php

use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DynamicValuesController;
use App\Http\Controllers\ForceAttendanceController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveAssignmentController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EventAssignmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index']);
// duita name login vayara maila  hatako hoi yo mathi ko chai
Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetPasswordMail'])->name('reset-password-mail');
Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('reset-password');
// Route::post('/reset-password', [ResetPasswordController::class, 'index'])->name('reset-password');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/permission-group', PermissionGroupController::class);
    Route::resource('/permission', PermissionController::class);
    Route::resource('/role', RoleController::class);

    Route::resource('/employee', EmployeeController::class);
    Route::resource('/event', EventController::class);
    Route::resource('/holiday', HolidayController::class);
    Route::resource('/shift', ShiftController::class);
    Route::resource('/shift-assignment', ShiftAssignmentController::class);
    Route::resource('/force-attendance', ForceAttendanceController::class);
    Route::resource('/leave', LeaveController::class);
    Route::resource('/leave-assignment', LeaveAssignmentController::class);
    Route::resource('/leave-application', LeaveApplicationController::class);

    Route::group(['prefix' => 'dynamic-values', 'as' => "dynamic_values."], function(){
       Route::get('/{setup}', [DynamicValuesController::class, 'getValues'])->name('index');
       Route::post('/save', [DynamicValuesController::class, 'save'])->name('save');
       Route::get('get/{id}', [DynamicValuesController::class, 'edit'])->name('edit');
    });

    Route::get('department/off/days/{id}', [DepartmentController::class, 'departmentOffDays'])->name('departmentOffDays');
    Route::post('department/off/days', [DepartmentController::class, 'assingOffDays'])->name('assignOffDays');

    Route::group(['prefix' => 'event-assignment', 'as' => 'event-assignment.'], function(){
        Route::get('create/{event_id?}', [EventAssignmentController::class, 'create'])->name('create');
        Route::post('store', [EventAssignmentController::class, 'store'])->name('store');
        Route::get('employee/{event_id}', [EventAssignmentController::class, 'event_employee_list'])->name('event_employee_list');
    });

    // ajax route
    Route::get('api/branch/{branch_id}', function($branch_id) {
        return getBranchDetails($branch_id);
    })->name('api.branch.show');
    Route::get('api/department/{department_id}', function($department_id) {
        return getDepartmentDetails($department_id);
    })->name('api.department.show');
    Route::get('api/employee/{employee_id}', function($employee_id) {
        return getEmployeeDetails($employee_id);
    })->name('api.employee.show');

    Route::get('/api/getEmployeeShift', [ForceAttendanceController::class, 'getEmployeeShift'])->name('api.get-employee-shift');

    Route::get('/quick-attendance',[AttendanceReportController::class, 'quickAttendanceReport'])->name('quick-attendance');
    Route::get('/monthly-attendance',[AttendanceReportController::class, 'monthlyAttendanceReport'])->name('monthly-attendance');
});

include(__DIR__.'/Routes/company.php');
