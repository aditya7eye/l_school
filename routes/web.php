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


Route::get('emp_type', 'EmployeeTypeController@emp_type_list');
Route::get('emp_type_edit', 'EmployeeTypeController@edit_emptype');
Route::post('emp_type/update', 'EmployeeTypeController@update');

Route::get('e', 'PayrollController@get_error_log');



/////////////////////////////////////////////////////////////////////////////////////



Route::group(['middleware' => 'usersession'], function () {
    Route::get('/dashboard','Master_Controller@dashboard');

//    Route::get('/','Employee_Controller@employeemanage');
    Route::get('/employee-manage','Employee_Controller@employeemanage');
    Route::get('edit_employee','Employee_Controller@edit_employee');
    Route::get('update_employee','Employee_Controller@update_employee');
    Route::get('employee-leave-left','Employee_Controller@employee_leave_left');
    Route::get('edit_employee_leave_left','Employee_Controller@edit_employee_leave_left');
    Route::get('update_employee_leave_left','Employee_Controller@update_employee_leave_left');
    Route::get('employee-leave-type','Employee_Controller@employee_leave_type');
    Route::get('edit_employee-leave-type','Employee_Controller@edit_leave_type');
    Route::get('update_leave_count','Employee_Controller@update_leave_count');

    Route::get('active_employee', 'Employee_Controller@active_employee');
    Route::get('inactive_employee','Employee_Controller@inactive_employee');

    Route::get('attendance_list','AttendanceController@attendance_list');
    Route::get('getAttendance','AttendanceController@attendance_list');
    Route::get('view_attendance_log','AttendanceController@view_attendance_log');
    Route::get('create-payroll','PayrollController@create_payrole');
    Route::get('generate_payroll','PayrollController@generate_payrole');
    Route::get('delete_payroll_temp','PayrollController@delete_payroll_temp');
    Route::get('view-payroll/{date}','PayrollController@payrole_list');
    Route::get('view-temp-payroll/{date}','PayrollController@temp_payrole_list');

    Route::get('edit_temp_payroll','PayrollController@edit_temp_payroll');
    Route::get('update_temp_payroll','PayrollController@update_temp_payroll');


    Route::get('convert_payroll/{tempid_date}','PayrollController@convert_payroll');

    Route::get('update_pf_form','Master_Controller@update_pf_form');
    Route::get('update_pf_list','Master_Controller@update_pf_list');
    Route::get('updatepfesic','Master_Controller@updatepfesic');

    Route::get('create_session','Master_Controller@create_session');
    Route::post('save_session','Master_Controller@save_session');
    Route::get('session_list','Master_Controller@session_list');
    Route::get('update_session_frm','Master_Controller@update_session_frm');
    Route::post('update_session','Master_Controller@update_session');
});

Route::get('/','LoginController@loginpage');
Route::get('logout','LoginController@logout');
Route::get('logincheck','LoginController@logincheck');

Route::get('test1','LoginController@test1');
