<?php

namespace App\Http\Controllers;

use App\EmployeeLeaveLeft;
use App\EmployeeModel;
use App\EmployeeType;
use App\SessionMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Employee_Controller extends Controller
{
    function employeemanage()
    {
        $employeelist = EmployeeModel::/*where(['RecordStatus' => 1])->*/
        orderBy('EmployeeId', 'desc')->get();
        return view('employee.employee')->with(['employeelist' => $employeelist]);
    }

    function edit_employee()
    {
        $emp = EmployeeModel::find(request('eid'));
        return view('employee.edit_employee')->with(['emp' => $emp]);
    }

    function update_employee()
    {
        $admin = EmployeeModel::find(request('eid'));
        $admin->EmployeeName = request('EmployeeName');
        $admin->EmployementType = request('EmployementType');
        $admin->employee_type_id = request('employee_type_id');
        $admin->DOJ = Carbon::parse(request('doj'))->format('Y-m-d');
        $admin->salary = request('salary');
        $admin->is_pf_applied = request('is_pf_applied');
        $admin->is_active = request('is_active');
        $admin->check_in = request('check_in');
        $admin->check_out = request('check_out');
        $admin->save();
        if ($admin->is_active == 1) {
            $session_master = SessionMaster::where(['is_active' => 1])->first();
            $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $admin->EmployeeId, 'session_id' => $session_master->id])->first();
            if (!isset($employee_leave_left)){
                $emp = new EmployeeLeaveLeft();
                $emp->session_id = $session_master->id;
                $emp->employee_id = $admin->EmployeeId;
                $emp->cl = 12;
                $emp->ml = 7;
                $emp->save();
            }
        }
        return Redirect::back()->with('message', 'Employee has been updated');
    }

    public function inactive_employee()
    {
        $item = EmployeeModel::find(request('e_id'));
        $item->is_active = 0;
        $item->save();
    }

    public function active_employee()
    {
        $item = EmployeeModel::find(request('e_id'));
        $item->is_active = 1;
        $item->save();
    }

    function employee_leave_left()
    {
        $ses = SessionMaster::where(['is_active' => 1])->first();
        $emp_leave_lefts = EmployeeLeaveLeft::where(['session_id' => $ses->id])->get();
        return view('employee.employee_leave_list')->with(['ses' => $ses, 'emp_leave_lefts' => $emp_leave_lefts]);
    }

    function edit_employee_leave_left()
    {
        $employee_leave_left = EmployeeLeaveLeft::find(request('eid'));
        return view('employee.edit_employee_leave_left')->with(['employee_leave_left' => $employee_leave_left]);
    }

    function update_employee_leave_left()
    {
        $admin = EmployeeLeaveLeft::find(request('lid'));
        $admin->cl = request('cl');
        $admin->ml = request('ml');
        $admin->gate_pass_min = request('gate_pass_min');
        $admin->save();
        return Redirect::back()->with('message', 'Leave left has been updated');
    }

    function employee_leave_type()
    {
        return view('employee.employee_leave_type');
    }

    function edit_leave_type()
    {
        $emp_type = EmployeeType::find(request('lid'));
        return view('employee_type.edit_leave_count')->with(['emp_type' => $emp_type]);
    }

    function update_leave_count()
    {
        $admin = EmployeeType::find(request('lid'));
        $admin->cl = request('cl');
        $admin->ml = request('ml');
        $admin->save();
        return Redirect::back()->with('message', 'Leave count has been updated');
    }



    // file_get_contents("http://api.msg91.com/api/sendhttp.php?sender=CONONE&route=4&mobiles=$user_master->contact&authkey=213418AONRGdnQ5ae96f62&country=91&message=Dear%20user,%20Password%20to%20login%20into%20connectingone%20is%20$otp");
    // file_get_contents("http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username= dreamdesire&password=password&sender=DESIGN&to=9302864646&message=Hello&reqid=1&format={json|text}&route_id=113");

    //http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username= dreamdesire&password=password&sender=DESIGN&to=9302864646&message=Hello&reqid=1&format={json|text}&route_id=113
}
