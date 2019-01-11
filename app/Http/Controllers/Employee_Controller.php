<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Employee_Controller extends Controller
{
   function employeemanage()
   {
       return view('Employee.employee');
   }
}
