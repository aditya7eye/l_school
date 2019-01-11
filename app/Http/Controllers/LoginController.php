<?php

namespace App\Http\Controllers;

use App\Admin_Model;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function loginpage()
    {
        return view('login');
    }

    function logincheck(Request $request)
    {
        $username = request('username');
        $password = request('password');
        $data = Admin_Model::where(['username' => $username, 'password' => $password, 'is_active' => 1])->first();
        if (isset($data)) {

            $request->session()->put('adminmaster', $data);
            return redirect('dashboard');
        } else {
            return redirect('/')->with('message', 'Username / Password Invalid');
        }
    }

    function logout(Request $request){

        $request->session()->forget('adminmaster');
        return redirect('/');
    }

}
