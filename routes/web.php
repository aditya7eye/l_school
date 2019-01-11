<?php

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
//////////////////////////////Admin master//////////////////////////////////////////

Route::get('/manage-admin','Master_Controller@addadmin');
Route::get('/insertadmin','Master_Controller@insertadmin');
Route::get('/del_admin','Master_Controller@del_admin');
Route::get('/update_admin_form','Master_Controller@update_admin_form');
Route::get('/updateadmindetails','Master_Controller@updateadmindetails');
Route::get('/manage-plan','Plan_Controller@addplan');


Route::resource('holiday', 'HolidayController');
Route::get('holiday_edit', 'HolidayController@editholiday');
Route::post('holiday/update', 'HolidayController@update');
Route::get('delete_holiday', 'HolidayController@destroy');


Route::get('e', 'PayrollController@get_error_log');



/////////////////////////////////////////////////////////////////////////////////////



Route::group(['middleware' => 'usersession'], function () {
    Route::get('/dashboard','Master_Controller@dashboard');

//    Route::get('/','Employee_Controller@employeemanage');
    Route::get('/employee-manage','Employee_Controller@employeemanage');

    Route::get('active_employee', 'Employee_Controller@active_employee');
    Route::get('inactive_employee','Employee_Controller@inactive_employee');

    Route::get('attendance_list','AttendanceController@attendance_list');
    Route::get('create-payrole','PayrollController@create_payrole');
    Route::get('generate_payrole','PayrollController@generate_payrole');
    Route::get('delete_payroll','PayrollController@delete_payroll');
    Route::get('view-payroll/{date}','PayrollController@payrole_list');
});

Route::get('/','LoginController@loginpage');
Route::get('logout','LoginController@logout');
Route::post('logincheck','LoginController@logincheck');
