<?php

namespace App\Http\Controllers;

use App\EmployeeModel;
use Illuminate\Http\Request;

class Employee_Controller extends Controller
{
   function employeemanage()
   {
       return view('Employee.employee');
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

    // file_get_contents("http://api.msg91.com/api/sendhttp.php?sender=CONONE&route=4&mobiles=$user_master->contact&authkey=213418AONRGdnQ5ae96f62&country=91&message=Dear%20user,%20Password%20to%20login%20into%20connectingone%20is%20$otp");
    // file_get_contents("http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username= dreamdesire&password=password&sender=DESIGN&to=9302864646&message=Hello&reqid=1&format={json|text}&route_id=113");

    //http://login.heightsconsultancy.com/API/WebSMS/Http/v1.0a/index.php?username= dreamdesire&password=password&sender=DESIGN&to=9302864646&message=Hello&reqid=1&format={json|text}&route_id=113
}
