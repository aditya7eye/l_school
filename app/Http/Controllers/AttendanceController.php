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
        $employee_list = EmployeeModel::where(['is_active' => 1])->orderBy('EmployeeId','desc')->get();
        return view('employee.attendance_list')->with(['employee_list' => $employee_list]);

    }
}
