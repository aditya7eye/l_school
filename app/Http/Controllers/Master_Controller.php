<?php

namespace App\Http\Controllers;

use App\Admin_Model;
session_start();


class Master_Controller extends Controller
{
    public function dashboard()
    {
        return view('AdminContent.dashboard');
    }
    function addadmin()
    {
        return view('AdminContent.add_admin');
    }
    function insertadmin()
    {
        $admin = new Admin_Model();
        $admin->name = request('name');
        $admin->username = request('username');
        $admin->password = request('password');
        $admin->save();
        return redirect('manage-admin')->with('message', 'Admin has been created');
    }
    function del_admin()
    {
        $data = Admin_Model::find(request('did'));
        $data->is_del = 1;
        $data->save();
        return 'done';
    }
    function update_admin_form()
    {
        $data = Admin_Model::find(request('uid'));
        return view('modal.update_admin')->with(['data' => $data]);
        
    }

    function updateadmindetails()
    {
        $admin = Admin_Model::find(request('uuid'));
        $admin->name = request('name');
        $admin->username = request('username');
        $admin->password = request('password');
        $admin->save();
        return redirect('manage-admin')->with('message', 'Admin has been updated');
    }

}
