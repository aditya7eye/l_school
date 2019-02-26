<?php

namespace App\Http\Controllers;

use App\Attendancelogs;
use App\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function attendance_list(Request $request)
    {
//        return $data = $request->session()->get('adminmaster');
//        $attendance_list = Attendancelogs::where(['is_active' => 1])->get();
        $year = $request->input('year');
        $month = $request->input('month');
        $employee_id = request('employee_id');
//        $attendance = DB::select("SELECT * FROM `attendancelogs` WHERE EmployeeId = $employee_id and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");
        $attendance = Attendancelogs::whereMonth('AttendanceDate', '=', $month)->whereYear('AttendanceDate', '=', $year)->where('EmployeeId', '=', $employee_id)->get();
        return view('employee.attendance_list')->with(['attendance' => $attendance, 'year' => $year, 'month' => $month, 'employee_id' => $employee_id]);

    }

    public function view_attendance_log(Request $request)
    {
//        return $data = $request->session()->get('adminmaster');
//        $attendance_list = Attendancelogs::where(['is_active' => 1])->get();
        $attDate = $request->input('att_date');
        $empcode = $request->input('emp_code');
        $table = $request->input('table');
        $devicelogs = DB::select("SELECT * FROM $table WHERE UserId = '$empcode' and LogDate like '%$attDate%' order by DeviceLogId ASC");
        return view('employee.attendance_log_list')->with(['devicelogs' => $devicelogs]);

    }
}
