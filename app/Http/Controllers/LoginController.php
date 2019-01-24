<?php

namespace App\Http\Controllers;

use App\Admin_Model;
use App\EmployeeLeaveLeft;
use App\EmployeeModel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function loginpage()
    {
        return view('login');
    }

    function logincheck(Request $request)
    {
//        dd(base64_encode(request('admin')));
        $username = request('username');
        $password = request('password');
        $admin_id = request('admin');
        if (isset($admin_id)) {
            $admin_id = base64_decode(request('admin'));
            $data = Admin_Model::find($admin_id);
            if (isset($data)) {
                $request->session()->put('adminmaster', $data);
                return redirect('dashboard');
            } else {
                return redirect('/')->with('message', 'Username / Password Invalid');
            }
        } else {
            $data = Admin_Model::where(['username' => $username, 'password' => $password, 'is_active' => 1])->first();
            if (isset($data)) {
                $request->session()->put('adminmaster', $data);
                return redirect('dashboard');
            } else {
                return redirect('/')->with('message', 'Username / Password Invalid');
            }
        }

    }

    function logout(Request $request)
    {

        $request->session()->forget('adminmaster');
        return redirect('/');
    }

    public function test1(Request $request)
    {
//        $empl = EmployeeModel::where(['is_active' => 1])->get();
//        foreach ($empl as $emp11) {
//            $emp = new EmployeeLeaveLeft();
//            $emp->session_id = 1;
//            $emp->employee_id = $emp11->EmployeeId;
//            $emp->cl = 12;
//            $emp->ml = 7;
//            $emp->save();
//        }

        $fullday = 0;
        $halfday = 0;
        $minCal = 1029;
        while ($minCal > 179) {
            if ($minCal > 360) {
                $minCal = $minCal - 360;
                $fullday++;
            } elseif ($minCal > 180) {
                $minCal = $minCal - 180;
                $halfday++;
            }
        }
        echo $fullday+($halfday/2);
    }

}
