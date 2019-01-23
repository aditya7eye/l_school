<?php

namespace App\Http\Controllers;

use App\Admin_Model;
use App\EmployeeLeaveLeft;
use App\EmployeeModel;
use App\PFESIC;
use App\SessionMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

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

    /**********************PF ESIC*******************/
    function updatepfesic()
    {
        $admin = PFESIC::find(1);
        $admin->pf = request('pf');
        $admin->esic = request('esic');
        $admin->gate_pass_min = request('gate_pass_min');
        $admin->save();
        return Redirect::back()->with('message', 'Percentage has been updated');
    }

    function update_pf_list()
    {
        return view('setting.pf_esic_cal');
    }

    function update_pf_form()
    {
        return view('setting.update_pf_esic');
    }
    /**********************PF ESIC*******************/


    /*************************Session**************************/
    function session_list()
    {
        return view('setting.session_list');
    }

    public function create_session()
    {
        return view('setting.create_session');
    }

    public function save_session()
    {
        $sess = new SessionMaster();
        $sess->start_date = Carbon::parse(request('date'))->format('Y-m-d');
        $sess->end_date = Carbon::parse(request('date'))->format('Y-m-d');
        $sess->is_active = 0;
        $sess->session = request('session_name');
        $sess->save();

        $empl = EmployeeModel::where(['is_active' => 1])->get();
        foreach ($empl as $emp11) {
            $emp = new EmployeeLeaveLeft();
            $emp->session_id = $sess->id;
            $emp->employee_id = $emp11->EmployeeId;
            $emp->cl = 12;
            $emp->ml = 7;
            $emp->save();
        }

        return Redirect::back()->with('message', 'Session has been added');
    }

    function update_session_frm()
    {
        $ses = SessionMaster::find(request('sid'));
        return view('setting.edit_session')->with(['ses' => $ses]);
    }

    public function update_session()
    {
        $sess = SessionMaster::get();

        foreach ($sess as $sesss) {
            if ($sesss->id == request('sid')) {
                $sesss->is_active = 1;
            } else {
                $sesss->is_active = 0;
            }
            $sesss->save();
        }
        $ses = SessionMaster::find(request('sid'));
        $ses->start_date = Carbon::parse(request('date'))->format('Y-m-d');
        $ses->end_date = Carbon::parse(request('date'))->format('Y-m-d');
        $ses->is_active = request('is_active');
        $ses->save();
        return Redirect::back()->with('message', 'Session has been updated');
    }
    /*************************Session**************************/


}
