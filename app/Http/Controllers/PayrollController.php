<?php

namespace App\Http\Controllers;

use App\Attendancelogs;
use App\EmployeeLeaveLeft;
use App\EmployeeLeaves;
use App\EmployeeModel;
use App\EmployeeType;
use App\ErrorLog;
use App\GatePass;
use App\Overtime;
use App\Payrole;
use App\PFESIC;
use App\SessionMaster;
use App\TempEmployeeLeaves;
use App\TempGatePass;
use App\TempOvertime;
use App\TempPayrole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Exception;

class PayrollController extends Controller
{
    /**
     * @param array $middleware
     */

    public function get_error_log()
    {
        $errors = ErrorLog::orderBy('id', 'desc')->paginate(20);
        $i = count($errors);
        if (count($errors) > 0) {

            foreach ($errors as $error) {
                echo $i . "<b>.  Error Msg: </b>" . $error->error_msg . "</br>" . "<b>Controller: </b>" . $error->controller_name . "</br>" . "<b>Function: </b>" . $error->function_name . "</br><b>Time: </b>" . $error->created_time . "</br></br>";
                $i--;
            }
        } else {
            return "No More Error Logs Available...";
        }
    }

    public function create_payrole()
    {
        $temp_payroles = DB::select("SELECT DISTINCT(temp_payrole.date), COUNT(id) as payrole_generated, created_time  FROM `temp_payrole` WHERE 1 GROUP by temp_payrole.date ORDER by temp_payrole.date desc");
        $payroles = DB::select("SELECT DISTINCT(payrole.date), COUNT(id) as payrole_generated, created_time  FROM `payrole` WHERE 1 GROUP by payrole.date ORDER by payrole.date desc");
        return view('employee.create_payrole')->with(['payroles' => $payroles, 'temp_payroles' => $temp_payroles]);
    }

    public function payrole_list($date)
    {
        try {
            $date = base64_decode($date);
            $payroles = Payrole::where(['date' => $date])->get();
            return view('employee.employee_payroll_list')->with(['payroles' => $payroles, 'date' => $date, 'temp' => 0]);
        } catch (Exception $e) {
            ErrorLog::store_error($e->getMessage(), 'PayrollController', 'payrole_list');
            return view('error.404');
            //return Redirect::back()->withErrors('Something went wrong');
        }
    }

    public function temp_payrole_list($date)
    {
        try {
            $date = base64_decode($date);
            $payroles = TempPayrole::where(['date' => $date])->get();
            return view('employee.employee_payroll_list')->with(['payroles' => $payroles, 'date' => $date, 'temp' => 1]);
        } catch (Exception $e) {
            ErrorLog::store_error($e->getMessage(), 'PayrollController', 'payrole_list');
            return view('error.404');
            //return Redirect::back()->withErrors('Something went wrong');
        }
    }


    function edit_temp_payroll()
    {
        $payroll = TempPayrole::find(request('tid'));
        return view('employee.edit_temp_payroll')->with(['payroll' => $payroll]);
    }

    function update_temp_payroll()
    {
        $grosssal = 0;
        $payroll = TempPayrole::find(request('tid'));
//        $session_master = SessionMaster::where(['is_active' => 1])->first();
//        $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $payroll->employee_id, 'session_id' => $session_master->id])->first();
        $total_pf = 0;
        $total_esic = 0;
        $absent = $payroll->absent_days - (request('cl') + request('ml'));
        $oneday_sal = $payroll->salary / $payroll->month_days;
        $total_deduction = $oneday_sal * ($absent + $payroll->lwp);
        $total_deduction = round($total_deduction, 2);
        $grosssal += $payroll->employee->salary - $payroll->total_gatepass - $total_deduction;
        if ($payroll->employee->is_pf_applied == 1) {
            $pf_esic = PFESIC::find(1);
            $total_pf = ($grosssal * $pf_esic->pf) / 100;
            $total_pf = round($total_pf, 2);
            if ($total_pf > 2040)
                $total_pf = 2040;
            $total_esic = (($grosssal - $total_pf) * $pf_esic->esic) / 100;
            $total_esic = round($total_esic, 2);
        }
        $total_deduction = $total_pf + $total_esic + $payroll->total_gatepass + $oneday_sal * ($absent + $payroll->lwp);
        $payout = $payroll->salary - $total_deduction;
        $payroll->modified_lwp = $absent + $payroll->lwp;
        $payroll->is_modified = 1;
        $payroll->cl = request('cl');
        $payroll->ml = request('ml');
//        $payrole_model->paid_leave = $paid_leave;
        $payroll->gross_salary = $grosssal;
        $payroll->total_pf = $total_pf;
        $payroll->total_esic = $total_esic;
        $payroll->total_deduction = $total_deduction;
        $payroll->payout = number_format((float)$payout, 2, '.', '');
        $payroll->save();
        return redirect('view-temp-payroll' . '/' . base64_encode($payroll->date))->with('message', 'Temporary Payroll has been updated');
//        return view('employee.edit_temp_payroll');
    }

    public function sunday_ina_month($month, $year)
    {
        $sundays = 0;
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $total_days; $i++)
            if (date('N', strtotime($year . '-' . $month . '-' . $i)) == 7)
                $sundays++;
        return $sundays;
    }

    public function generate_payrole(Request $request)
    {
        $employee_id = $request->input('employee_id');

        $year = $request->input('year');
        $month = $request->input('month');
        $pdate_check = $month . "," . $year;

//        return $employee_id;
        if ($employee_id != '') {
            $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $employee_list = $employee_id[0] == 0 ? EmployeeModel::where(['is_active' => 1])->get() : EmployeeModel::where(['is_active' => 1])->whereIn('EmployeeId', $employee_id)->get();

            for ($i = 1; $i <= 1; $i++)
                $all_date_arr[] = $year . '-' . (($month < 10) ? '0' . $month : $month) . '-' . (($i < 10) ? '0' . $i : $i);

//            $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31'");//2;

            $weekend = $this->sunday_ina_month($month, $year);
            $session_master = SessionMaster::where(['is_active' => 1])->first();

            foreach ($employee_list as $employee) {
                $parole_check = TempPayrole::where(['date' => $pdate_check, 'employee_id' => $employee->EmployeeId])->first();
                if (!isset($parole_check)) {

                    $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31' and FIND_IN_SET('$employee->EmployeeId',employee_id) and is_active = 1");//2;
                    $total_working_days = $month_days - count($holidays) - $weekend;

                    $late_min = 0;
                    $late_min1 = 0;
                    $late_min2 = 0;
                    $overtime_min = 0;
                    $gtm = 0;
                    $gatepassmin = 0;
                    $absent = 0;
                    $emp_present_days = 0;
                    $late_count = 0;
                    $UserId = $employee->emp_code;
                    $lwp = 0;
                    $paid_leave = 0;
                    $total_pf = 0;
                    $total_esic = 0;
                    $total_gatepass = 0;
                    $total_deduction = 0;

                    $grosssal = 0;
                    $payout = 0;
                    $emp_cat_row = EmployeeType::where(['id' => $employee->type_id])->first();

                    $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $employee->EmployeeId, 'session_id' => $session_master->id])->first();

                    $given_max_cl = isset($emp_cat_row->cl) ? $emp_cat_row->cl : 12;

                    $given_max_ml = isset($emp_cat_row->ml) ? $emp_cat_row->ml : 7;


                    $taken_cl = $given_max_cl - isset($employee_leave_left) ? $employee_leave_left->cl : 0;
                    $taken_ml = $given_max_ml - isset($employee_leave_left) ? $employee_leave_left->ml : 0;

                    $left_max_cl = $given_max_cl - $taken_cl;
                    $left_max_ml = $given_max_ml - $taken_ml;

                    $present_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as present_days FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");
                    $attendance_records = DB::select("SELECT * FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");


                    /*********************Late Min/Count Calculation***************************/
                    if ($present_days->present_days > 0) {
                        //echo  $sql=$this->db->last_query(); die;


//                        $absent = $total_working_days - $present_days->present_days;

                        if (count($attendance_records) > 0) {
                            $PFESIC = PFESIC::find(1);
                            foreach ($attendance_records as $value) {
                                $employee_arr1 = array();
                                $att_date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                $cintime = $att_date . ' ' . $employee->check_in;
                                $couttime = $att_date . ' ' . $employee->check_out;
                                /*holiday Overtime if present*/
                                $holiday_present_check = DB::selectOne("SELECT * FROM `holiday` WHERE date = '$att_date' and FIND_IN_SET('$employee->EmployeeId',employee_id) and is_active = 1");//2;
                                //
                                $table = "devicelogs_" . $month . "_" . $year;
                                $device_logs_ = DB::select("SELECT * FROM $table WHERE UserId = $employee->EmployeeCode and LogDate like '%$att_date%' ORDER by LogDate ASC");//2;
                                $device_logs_count = DB::selectOne("SELECT COUNT(DeviceLogId) as deviceCount FROM $table WHERE UserId = $employee->EmployeeCode and LogDate like '%$att_date%'");//2;
                                if ($device_logs_count->deviceCount % 2 != 0) {
                                    if ($device_logs_count->deviceCount >= 3) {

                                        $datetime1 = new DateTime($device_logs_[0]->LogDate);
                                        $datetime2 = new DateTime($device_logs_[1]->LogDate);
                                        $interval = $datetime1->diff($datetime2);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours = $interval->format('%h');
                                        $minutes = $interval->format('%i');
                                        $check5min = ($hours * 60 + $minutes);
                                        if ($check5min < 5) {
                                            $second_dev_id = $device_logs_[1]->DeviceLogId;
                                            DB::select("delete from $table where DeviceLogId = $second_dev_id");
                                        }
                                        $now = Carbon::now();
                                        $last_enter_time = $device_logs_[$device_logs_count->deviceCount - 1]->LogDate;
                                        $last_enter_id = $device_logs_[$device_logs_count->deviceCount - 1]->DeviceLogId;
                                        $second_last_enter_time = $device_logs_[$device_logs_count->deviceCount - 2]->LogDate;
                                        if ($last_enter_time > $couttime) {
                                            DB::select("update $table set C1 = 'in' and DeviceId = '166' AND LogDate = '$second_last_enter_time' where DeviceLogId = $last_enter_id");
                                            DB::select("INSERT INTO $table(`DownloadDate`, `DeviceId`, `UserId`, `LogDate`,`C1`) VALUES ('$now',166,$employee->EmployeeCode,'$last_enter_time','out')");
                                        } else {
                                            DB::select("INSERT INTO $table(`DownloadDate`, `DeviceId`, `UserId`, `LogDate`,`C1`) VALUES ('$now',166,$employee->EmployeeCode,'$couttime','out')");
                                        }
                                    } else {
                                        $now = Carbon::now();
                                        $last_enter_time = $device_logs_[0]->LogDate;
                                        $last_enter_id = $device_logs_[0]->DeviceLogId;
                                        if ($last_enter_time > $couttime) {
                                            DB::select("update $table set C1 = 'out' where DeviceLogId = $last_enter_id");
                                            DB::select("INSERT INTO $table(`DownloadDate`, `DeviceId`, `UserId`, `LogDate`,`C1`) VALUES ('$now',166,$employee->EmployeeCode,'$cintime','in')");
                                        } else {
//                                            DB::select("update $table set C1 = 'in' and LogDate = '$cintime' where DeviceLogId = $last_enter_id");
                                            DB::select("update $table set C1 = 'in' where DeviceLogId = $last_enter_id");

                                            DB::select("INSERT INTO $table(`DownloadDate`, `DeviceId`, `UserId`, `LogDate`,`C1`) VALUES ('$now',166,$employee->EmployeeCode,'$couttime','out')");
                                        }

                                    }

//                                    DB::select("UPDATE `attendancelogs` SET OutTime = '$couttime' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                }

                                $device_logs = DB::select("SELECT * FROM $table WHERE UserId = $employee->EmployeeCode and LogDate like '%$att_date%' ORDER by LogDate ASC");//2;

//                                $array_ = array();
                                $indate = $device_logs[0]->LogDate;
                                DB::select("UPDATE `attendancelogs` SET InTime = '$indate' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                DB::select("UPDATE `attendancelogs` SET C1 = '' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                DB::select("UPDATE `attendancelogs` SET C2 = '0' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                foreach ($device_logs as $index => $device_log1) {
                                    if ($index % 2 == 0) {
                                        if ($index >= 2 && $device_log1->LogDate < $couttime) {
                                            $datetime1 = new DateTime($device_logs[$index - 1]->LogDate);
                                            $datetime2 = new DateTime($device_log1->LogDate);
                                            $interval = $datetime1->diff($datetime2);
                                            $hours = $interval->format('%h');
                                            $minutes = $interval->format('%i');
                                            $extra_gp = ($hours * 60 + $minutes);
                                            DB::select("update $table set C2 = $extra_gp where DeviceLogId = $device_log1->DeviceLogId");
//                                            DB::select("UPDATE `attendancelogs` SET C2 = C2 + $extra_gp WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");

                                            $gatepass = new TempGatePass();
                                            $gatepass->employee_id = $employee->EmployeeId;
                                            $gatepass->late_min = $extra_gp;
                                            $gatepass->date = $att_date;
                                            $gatepass->session_id = $session_master->id;
                                            $gatepass->comment = "Between Working hours checkin/checkout min $extra_gp";
                                            $gatepass->save();

                                            $Att_Save = Attendancelogs::where(['AttendanceLogId' => $value->AttendanceLogId])->first();
                                            $Att_Save->C1 .= "</br>Between Working hours checkin/checkout min $extra_gp";
                                            $Att_Save->C2 += $extra_gp;
                                            $Att_Save->save();

                                        }
//                                        dd("if%2_".$index);
//                                        $array_[] ="$device_log1->DeviceLogId"."_if";
                                        DB::select("update $table set C1 = 'in' where DeviceLogId = $device_log1->DeviceLogId");
//                                        DB::select("UPDATE `attendancelogs` SET OutTime = '$couttime' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                    } else {
                                        DB::select("UPDATE `attendancelogs` SET OutTime = '$device_log1->LogDate' WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                        DB::select("update $table set C1 = 'out' where DeviceLogId = $device_log1->DeviceLogId");

                                        if ($index >= 2 && $device_log1->LogDate < $couttime) {
                                            $datetime1 = new DateTime($device_logs[$index - 1]->LogDate);
                                            $datetime2 = new DateTime($device_log1->LogDate);
                                            $interval = $datetime1->diff($datetime2);
                                            $hours = $interval->format('%h');
                                            $minutes = $interval->format('%i');
                                            $extra_gp = ($hours * 60 + $minutes);
                                            DB::select("update $table set C2 = $extra_gp where DeviceLogId = $device_log1->DeviceLogId");
//                                            DB::select("UPDATE `attendancelogs` SET C2 = C2 + $extra_gp WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");

                                            $gatepass = new TempGatePass();
                                            $gatepass->employee_id = $employee->EmployeeId;
                                            $gatepass->late_min = $extra_gp;
                                            $gatepass->date = $att_date;
                                            $gatepass->session_id = $session_master->id;
                                            $gatepass->comment = "Between Working hours checkin/checkout min $extra_gp";
                                            $gatepass->save();

                                            $Att_Save = Attendancelogs::where(['AttendanceLogId' => $value->AttendanceLogId])->first();
                                            $Att_Save->C1 .= "</br>Gatepass Entry $extra_gp min(In After $PFESIC->gate_pass_min min)";
                                            $Att_Save->C2 += $extra_gp;
                                            $Att_Save->save();

                                        }
                                    }/* else {
//                                        $array_[] = "$device_log->DeviceLogId"."_else";
                                        DB::select("update $table set C1 = 'in' where DeviceLogId = $device_log->DeviceLogId");
                                    }*/
                                }
//                                dd($att_date);
//                                dd($array_);

                                /*holiday Overtime if present*/
                                $att_check_entry = DB::selectOne("select * from `attendancelogs` WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");
                                if (!isset($holiday_present_check)) {
                                    $emp_present_days += 1;
                                    if ($att_check_entry->InTime > $cintime) {
                                        $datetime1 = new DateTime($cintime);
                                        $datetime2 = new DateTime($att_check_entry->InTime);
                                        $interval = $datetime1->diff($datetime2);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours = $interval->format('%h');
                                        $minutes = $interval->format('%i');
                                        $lmt = ($hours * 60 + $minutes);
                                        if ($lmt > $PFESIC->gate_pass_min) {
                                            $gatepass = new TempGatePass();
                                            $gatepass->employee_id = $employee->EmployeeId;
                                            $gatepass->late_min = $lmt;
                                            $gatepass->date = date_format(date_create($att_check_entry->AttendanceDate), "Y-m-d");
                                            $gatepass->session_id = $session_master->id;
                                            $gatepass->comment = "In After $PFESIC->gate_pass_min min Late";
                                            $gatepass->save();

                                            $Att_Save = Attendancelogs::where(['AttendanceLogId' => $att_check_entry->AttendanceLogId])->first();
                                            $Att_Save->C1 .= "</br>Gatepass Entry $lmt min(In After $PFESIC->gate_pass_min min)";
                                            $Att_Save->C2 += $lmt;
                                            $Att_Save->save();

//                                            DB::select("UPDATE `attendancelogs` SET C2 = C2 + $lmt WHERE EmployeeId = $employee->EmployeeId and  AttendanceDate like '%$att_date%'");

                                            $gtm += $lmt;
                                        } else {
                                            $late_min1 += $lmt;

                                            $Att_Save = Attendancelogs::where(['AttendanceLogId' => $att_check_entry->AttendanceLogId])->first();
                                            $Att_Save->C1 .= "</br>Late Entry $lmt min";
                                            $Att_Save->save();
                                        }
                                        $late_count++;
                                    }
                                    if ($att_check_entry->OutTime < $couttime) {
                                        $datetime11 = new DateTime($couttime);
                                        $datetime21 = new DateTime($att_check_entry->OutTime);
                                        $interval1 = $datetime11->diff($datetime21);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours1 = $interval1->format('%h');
                                        $minutes1 = $interval1->format('%i');
                                        $late_min2 += ($hours1 * 60 + $minutes1);

                                        $gatepass = new TempGatePass();
                                        $gatepass->employee_id = $employee->EmployeeId;
                                        $gatepass->late_min = $late_min2;
                                        $gatepass->date = date_format(date_create($att_check_entry->AttendanceDate), "Y-m-d");
                                        $gatepass->session_id = $session_master->id;
                                        $gatepass->comment = "Out Before $couttime";
                                        $gatepass->save();

                                        $Att_Save = Attendancelogs::where(['AttendanceLogId' => $att_check_entry->AttendanceLogId])->first();
                                        $Att_Save->C1 .= "</br>Gatepass Entry $late_min2 min(Out Before $couttime)";
                                        $Att_Save->C2 += $late_min2;
                                        $Att_Save->save();

                                        $gtm += $late_min2;

                                    } else {
                                        $datetime11 = new DateTime($att_check_entry->OutTime);
                                        $datetime21 = new DateTime($couttime);
                                        $interval1 = $datetime11->diff($datetime21);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours1 = $interval1->format('%h');
                                        $minutes1 = $interval1->format('%i');
                                        $overtime_min += ($hours1 * 60 + $minutes1);

                                        $overtime = new TempOvertime();
                                        $overtime->employee_id = $employee->EmployeeId;
                                        $overtime->overtime_min = $overtime_min;
                                        $overtime->date = date_format(date_create($att_check_entry->AttendanceDate), "Y-m-d");
                                        $overtime->session_id = $session_master->id;
                                        $overtime->save();
                                    }
                                    $late_min = $late_min1 + $late_min2;
//                                    $gatepassmin += $gtm;
//                        $pdate_arr[] = $value->AttendanceDate;
                                } else {
                                    $datetime11 = new DateTime($cintime);
                                    $datetime21 = new DateTime($couttime);
                                    $interval1 = $datetime11->diff($datetime21);
                                    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                    $hours1 = $interval1->format('%h');
                                    $minutes1 = $interval1->format('%i');
                                    $overtime_min += ($hours1 * 60 + $minutes1);

                                    $overtime = new TempOvertime();
                                    $overtime->employee_id = $employee->EmployeeId;
                                    $overtime->overtime_min = $overtime_min;
                                    $overtime->date = date_format(date_create($att_check_entry->AttendanceDate), "Y-m-d");
                                    $overtime->session_id = $session_master->id;
                                    $overtime->save();
                                }

                            }

                            $absent = $total_working_days - $emp_present_days;
                        }
                    }
                    /*********************Late Min/Count Calculation***************************/


                    /*********************Lwp(Leave Without Pay) Calculation***************************/
                    $oneday_sal = $employee->salary / $month_days;  ///Salary Per Day
                    if ($late_count > 2) {
                        if ($late_count == 3)
                            $lwp = $lwp + 1;
                        else
                            $lwp = $lwp + intval($late_count / 2);

                    }
                    /*********************Lwp(Leave Without Pay) Calculation***************************/


                    /*********************May June Expect Month Calculation***************************/
                    if ($month != 5 && $month != 6) {
                        if (($left_max_cl > 0 || $left_max_ml > 0) && $lwp > 0) {

                            $data_leave['date'] = $month . ',' . $year;


//                    $leave_obj = $this->db->get_where('leave', array('date' => $data_leave['date'], 'emp_id' => $employee->id, 'session_id' => $session_master->session))->row();

                            $leave_obj = EmployeeLeaves::where(['date' => $data_leave['date'], 'employee_id' => $employee->id, 'session_id' => $session_master->id])->get();
                            //$query= $payroll ;

                            if (count($leave_obj) > 0) {

                                $leave_model = new TempEmployeeLeaves();
                                $leave_model->date = $month . ',' . $year;
                                $leave_model->employee_id = $employee->id;
                                $leave_model->session_id = $session_master->id;
                                $leave_model->leave_type = 'CL';
                                $leave_model->save();
//                            if (isset($employee_leave_left)) {
//                                $employee_leave_left->cl -= 1;
//                                $employee_leave_left->save();
//                            }
//                        $this->db->insert('leave', $data_leave);
                                $paid_leave++;
                                $lwp--;
                                $left_max_cl--;
                                if ($left_max_cl > 0 && $lwp > 0) {
                                    $leave_model = new TempEmployeeLeaves();
                                    $leave_model->date = $month . ',' . $year;
                                    $leave_model->employee_id = $employee->id;
                                    $leave_model->session_id = $session_master->id;
                                    $leave_model->leave_type = 'CL';
                                    $leave_model->save();
//                                if (isset($employee_leave_left)) {
//                                    $employee_leave_left->cl -= 1;
//                                    $employee_leave_left->save();
//                                }
                                    $paid_leave++;
                                    $lwp--;
                                    $left_max_cl--;
                                    if ($left_max_ml > 0 && $lwp > 0) {
                                        $leave_model = new TempEmployeeLeaves();
                                        $leave_model->date = $month . ',' . $year;
                                        $leave_model->employee_id = $employee->id;
                                        $leave_model->session_id = $session_master->id;
                                        $leave_model->leave_type = 'ML';
                                        $leave_model->save();
//                                    if (isset($employee_leave_left)) {
//                                        $employee_leave_left->ml -= 1;
//                                        $employee_leave_left->save();
//                                    }
                                        $paid_leave++;
                                        $lwp--;
                                        $left_max_ml--;
                                    }
                                    if ($left_max_ml > 0 && $lwp > 0) {
                                        $leave_model = new TempEmployeeLeaves();
                                        $leave_model->date = $month . ',' . $year;
                                        $leave_model->employee_id = $employee->id;
                                        $leave_model->session_id = $session_master->id;
                                        $leave_model->leave_type = 'ML';
                                        $leave_model->save();
//                                    if (isset($employee_leave_left)) {
//                                        $employee_leave_left->ml -= 1;
//                                        $employee_leave_left->save();
//                                    }

                                        $paid_leave++;
                                        $lwp--;
                                        $left_max_ml--;
                                    }

                                }
                            }

                        }
                    }
                    /*********************May June Expect Month Calculation***************************/

                    /*********************Gross Salary Deduction Calculation***************************/
//                    dd($gtm);
                    $fullday = 0;
                    $halfday = 0;
                    $minCal = $employee_leave_left->gate_pass_min + $gtm;
                    while ($minCal > 179) {
                        if ($minCal > 360) {
                            $minCal = $minCal - 360;
                            $fullday++;
                        } elseif ($minCal > 180) {
                            $minCal = $minCal - 180;
                            $halfday++;
                        }
                    }
                    $gtDays = $fullday + ($halfday / 2);
                    $total_gatepass += $oneday_sal * $gtDays;
                    $total_deduction = $oneday_sal * ($lwp + $absent);
                    $total_deduction = round($total_deduction, 2);


                    $grosssal += $employee->salary - $total_gatepass - $total_deduction;

                    if ($employee->is_pf_applied == 1) {
                        $pf_esic = PFESIC::find(1);
                        $total_pf = ($grosssal * $pf_esic->pf) / 100;
                        $total_pf = round($total_pf, 2);
                        if ($total_pf > 2040)
                            $total_pf = 2040;
                        $total_esic = (($grosssal - $total_pf) * $pf_esic->esic) / 100;
                        $total_esic = round($total_esic, 2);
                    }

                    $total_deduction = $total_pf + $total_esic + $total_gatepass + $oneday_sal * ($lwp + $absent);
                    $payout = $employee->salary - $total_deduction;
                    /*********************Gross Salary Deduction Calculation***************************/

                    $payrole_model = new TempPayrole();
                    $payrole_model->employee_id = $employee->EmployeeId;
                    $payrole_model->month_days = $month_days;
                    $payrole_model->holidays = count($holidays);
                    $payrole_model->weekend_days = $weekend;
                    $payrole_model->working_days = $total_working_days;
                    $payrole_model->present_days = $emp_present_days; //$present_days->present_days;
                    $payrole_model->absent_days = $absent;
                    $payrole_model->late_minute = $late_min;
                    $payrole_model->previous_gatepassmin = $employee_leave_left->gate_pass_min;
                    $payrole_model->gatepassmin = $gtm;
                    $payrole_model->gt_full_day = $fullday;
                    $payrole_model->gt_half_day = $halfday;
                    $payrole_model->overtime_min = $overtime_min;
                    $payrole_model->total_gatepass = $total_gatepass;
                    $payrole_model->late_count = $late_count;
                    $payrole_model->lwp = $lwp;
                    $payrole_model->paid_leave = $paid_leave;
                    $payrole_model->salary = $employee->salary;
                    $payrole_model->gross_salary = $grosssal;
                    $payrole_model->total_pf = $total_pf;
                    $payrole_model->total_esic = $total_esic;
                    $payrole_model->total_deduction = $total_deduction;
                    $payrole_model->payout = number_format((float)$payout, 2, '.', '');
                    $payrole_model->date = $month . ',' . $year;
                    $payrole_model->session_id = $session_master->id;
                    $payrole_model->created_time = Carbon::now('Asia/Kolkata');
                    $payrole_model->save();

                } else {
                    return Redirect::back()->with('errmessage', "Temp Payroll already generated for $employee->EmployeeName");
                }
            }
            return redirect('create-payroll')->with('message', 'Temporary Payroll has been generated');
        } else {
            return Redirect::back()->with('errmessage', 'Please select any employee');
        }
    }

    public function generate_payrole_running(Request $request)
    {
        $employee_id = $request->input('employee_id');

        $year = $request->input('year');
        $month = $request->input('month');
        $pdate_check = $month . "," . $year;

//        return $employee_id;
        if ($employee_id != '') {
            $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $employee_list = $employee_id[0] == 0 ? EmployeeModel::where(['is_active' => 1])->get() : EmployeeModel::where(['is_active' => 1])->whereIn('EmployeeId', $employee_id)->get();

            for ($i = 1; $i <= 1; $i++)
                $all_date_arr[] = $year . '-' . (($month < 10) ? '0' . $month : $month) . '-' . (($i < 10) ? '0' . $i : $i);

//            $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31'");//2;

            $weekend = $this->sunday_ina_month($month, $year);
            $session_master = SessionMaster::where(['is_active' => 1])->first();

            foreach ($employee_list as $employee) {
                $parole_check = TempPayrole::where(['date' => $pdate_check, 'employee_id' => $employee->EmployeeId])->first();
                if (!isset($parole_check)) {

                    $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31' and FIND_IN_SET('$employee->EmployeeId',employee_id) and is_active = 1");//2;
                    $total_working_days = $month_days - count($holidays) - $weekend;

                    $late_min = 0;
                    $late_min1 = 0;
                    $late_min2 = 0;
                    $overtime_min = 0;
                    $gtm = 0;
                    $gatepassmin = 0;
                    $absent = 0;
                    $emp_present_days = 0;
                    $late_count = 0;
                    $UserId = $employee->emp_code;
                    $lwp = 0;
                    $paid_leave = 0;
                    $total_pf = 0;
                    $total_esic = 0;
                    $total_gatepass = 0;
                    $total_deduction = 0;

                    $grosssal = 0;
                    $payout = 0;
                    $emp_cat_row = EmployeeType::where(['id' => $employee->type_id])->first();

                    $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $employee->EmployeeId, 'session_id' => $session_master->id])->first();

                    $given_max_cl = isset($emp_cat_row->cl) ? $emp_cat_row->cl : 12;

                    $given_max_ml = isset($emp_cat_row->ml) ? $emp_cat_row->ml : 7;


                    $taken_cl = $given_max_cl - isset($employee_leave_left) ? $employee_leave_left->cl : 0;
                    $taken_ml = $given_max_ml - isset($employee_leave_left) ? $employee_leave_left->ml : 0;

                    $left_max_cl = $given_max_cl - $taken_cl;
                    $left_max_ml = $given_max_ml - $taken_ml;

                    $present_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as present_days FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");
                    $attendance_records = DB::select("SELECT * FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");


                    /*********************Late Min/Count Calculation***************************/
                    if ($present_days->present_days > 0) {
                        //echo  $sql=$this->db->last_query(); die;


//                        $absent = $total_working_days - $present_days->present_days;

                        if (count($attendance_records) > 0) {
                            $PFESIC = PFESIC::find(1);
                            foreach ($attendance_records as $value) {
                                $employee_arr1 = array();
                                $att_date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                /*holiday Overtime if present*/
                                $holiday_present_check = DB::selectOne("SELECT * FROM `holiday` WHERE date = '$att_date' and FIND_IN_SET('$employee->EmployeeId',employee_id) and is_active = 1");//2;
                                /*holiday Overtime if present*/
                                $cintime = $att_date . ' ' . $employee->check_in;
                                $couttime = $att_date . ' ' . $employee->check_out;
                                if (!isset($holiday_present_check)) {
                                    $emp_present_days += 1;
                                    if ($value->InTime > $cintime) {
                                        $datetime1 = new DateTime($cintime);
                                        $datetime2 = new DateTime($value->InTime);
                                        $interval = $datetime1->diff($datetime2);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours = $interval->format('%h');
                                        $minutes = $interval->format('%i');
                                        $lmt = ($hours * 60 + $minutes);
                                        if ($lmt > $PFESIC->gate_pass_min) {
                                            $gatepass = new TempGatePass();
                                            $gatepass->employee_id = $employee->EmployeeId;
                                            $gatepass->late_min = $lmt;
                                            $gatepass->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                            $gatepass->session_id = $session_master->id;
                                            $gatepass->save();
                                            $gtm += $lmt;
                                        } else {
                                            $late_min1 += $lmt;
                                        }
                                        $late_count++;
                                    }
                                    if ($value->OutTime < $couttime) {
                                        $datetime11 = new DateTime($couttime);
                                        $datetime21 = new DateTime($value->OutTime);
                                        $interval1 = $datetime11->diff($datetime21);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours1 = $interval1->format('%h');
                                        $minutes1 = $interval1->format('%i');
                                        $late_min2 += ($hours1 * 60 + $minutes1);

                                        $gatepass = new TempGatePass();
                                        $gatepass->employee_id = $employee->EmployeeId;
                                        $gatepass->late_min = $late_min2;
                                        $gatepass->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                        $gatepass->session_id = $session_master->id;
                                        $gatepass->save();
                                        $gtm += $late_min2;

                                    } else {
                                        $datetime11 = new DateTime($value->OutTime);
                                        $datetime21 = new DateTime($couttime);
                                        $interval1 = $datetime11->diff($datetime21);
                                        //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                        $hours1 = $interval1->format('%h');
                                        $minutes1 = $interval1->format('%i');
                                        $overtime_min += ($hours1 * 60 + $minutes1);

                                        $overtime = new TempOvertime();
                                        $overtime->employee_id = $employee->EmployeeId;
                                        $overtime->overtime_min = $overtime_min;
                                        $overtime->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                        $overtime->session_id = $session_master->id;
                                        $overtime->save();
                                    }
                                    $late_min = $late_min1 + $late_min2;
//                                    $gatepassmin += $gtm;
//                        $pdate_arr[] = $value->AttendanceDate;
                                } else {
                                    $datetime11 = new DateTime($cintime);
                                    $datetime21 = new DateTime($couttime);
                                    $interval1 = $datetime11->diff($datetime21);
                                    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                    $hours1 = $interval1->format('%h');
                                    $minutes1 = $interval1->format('%i');
                                    $overtime_min += ($hours1 * 60 + $minutes1);

                                    $overtime = new TempOvertime();
                                    $overtime->employee_id = $employee->EmployeeId;
                                    $overtime->overtime_min = $overtime_min;
                                    $overtime->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                    $overtime->session_id = $session_master->id;
                                    $overtime->save();
                                }

                            }

                            $absent = $total_working_days - $emp_present_days;
                        }
                    }
                    /*********************Late Min/Count Calculation***************************/


                    /*********************Lwp(Leave Without Pay) Calculation***************************/
                    $oneday_sal = $employee->salary / $month_days;  ///Salary Per Day
                    if ($late_count > 2) {
                        if ($late_count == 3)
                            $lwp = $lwp + 1;
                        else
                            $lwp = $lwp + intval($late_count / 2);

                    }
                    /*********************Lwp(Leave Without Pay) Calculation***************************/


                    /*********************May June Expect Month Calculation***************************/
                    if ($month != 5 && $month != 6) {
                        if (($left_max_cl > 0 || $left_max_ml > 0) && $lwp > 0) {

                            $data_leave['date'] = $month . ',' . $year;


//                    $leave_obj = $this->db->get_where('leave', array('date' => $data_leave['date'], 'emp_id' => $employee->id, 'session_id' => $session_master->session))->row();

                            $leave_obj = EmployeeLeaves::where(['date' => $data_leave['date'], 'employee_id' => $employee->id, 'session_id' => $session_master->id])->get();
                            //$query= $payroll ;

                            if (count($leave_obj) > 0) {

                                $leave_model = new TempEmployeeLeaves();
                                $leave_model->date = $month . ',' . $year;
                                $leave_model->employee_id = $employee->id;
                                $leave_model->session_id = $session_master->id;
                                $leave_model->leave_type = 'CL';
                                $leave_model->save();
//                            if (isset($employee_leave_left)) {
//                                $employee_leave_left->cl -= 1;
//                                $employee_leave_left->save();
//                            }
//                        $this->db->insert('leave', $data_leave);
                                $paid_leave++;
                                $lwp--;
                                $left_max_cl--;
                                if ($left_max_cl > 0 && $lwp > 0) {
                                    $leave_model = new TempEmployeeLeaves();
                                    $leave_model->date = $month . ',' . $year;
                                    $leave_model->employee_id = $employee->id;
                                    $leave_model->session_id = $session_master->id;
                                    $leave_model->leave_type = 'CL';
                                    $leave_model->save();
//                                if (isset($employee_leave_left)) {
//                                    $employee_leave_left->cl -= 1;
//                                    $employee_leave_left->save();
//                                }
                                    $paid_leave++;
                                    $lwp--;
                                    $left_max_cl--;
                                    if ($left_max_ml > 0 && $lwp > 0) {
                                        $leave_model = new TempEmployeeLeaves();
                                        $leave_model->date = $month . ',' . $year;
                                        $leave_model->employee_id = $employee->id;
                                        $leave_model->session_id = $session_master->id;
                                        $leave_model->leave_type = 'ML';
                                        $leave_model->save();
//                                    if (isset($employee_leave_left)) {
//                                        $employee_leave_left->ml -= 1;
//                                        $employee_leave_left->save();
//                                    }
                                        $paid_leave++;
                                        $lwp--;
                                        $left_max_ml--;
                                    }
                                    if ($left_max_ml > 0 && $lwp > 0) {
                                        $leave_model = new TempEmployeeLeaves();
                                        $leave_model->date = $month . ',' . $year;
                                        $leave_model->employee_id = $employee->id;
                                        $leave_model->session_id = $session_master->id;
                                        $leave_model->leave_type = 'ML';
                                        $leave_model->save();
//                                    if (isset($employee_leave_left)) {
//                                        $employee_leave_left->ml -= 1;
//                                        $employee_leave_left->save();
//                                    }

                                        $paid_leave++;
                                        $lwp--;
                                        $left_max_ml--;
                                    }

                                }
                            }

                        }
                    }
                    /*********************May June Expect Month Calculation***************************/

                    /*********************Gross Salary Deduction Calculation***************************/
//                    dd($gtm);
                    $fullday = 0;
                    $halfday = 0;
                    $minCal = $employee_leave_left->gate_pass_min + $gtm;
                    while ($minCal > 179) {
                        if ($minCal > 360) {
                            $minCal = $minCal - 360;
                            $fullday++;
                        } elseif ($minCal > 180) {
                            $minCal = $minCal - 180;
                            $halfday++;
                        }
                    }
                    $gtDays = $fullday + ($halfday / 2);
                    $total_gatepass += $oneday_sal * $gtDays;
                    $total_deduction = $oneday_sal * ($lwp + $absent);
                    $total_deduction = round($total_deduction, 2);


                    $grosssal += $employee->salary - $total_gatepass - $total_deduction;

                    if ($employee->is_pf_applied == 1) {
                        $pf_esic = PFESIC::find(1);
                        $total_pf = ($grosssal * $pf_esic->pf) / 100;
                        $total_pf = round($total_pf, 2);
                        if ($total_pf > 2040)
                            $total_pf = 2040;
                        $total_esic = (($grosssal - $total_pf) * $pf_esic->esic) / 100;
                        $total_esic = round($total_esic, 2);
                    }

                    $total_deduction = $total_pf + $total_esic + $total_gatepass + $oneday_sal * ($lwp + $absent);
                    $payout = $employee->salary - $total_deduction;
                    /*********************Gross Salary Deduction Calculation***************************/

                    $payrole_model = new TempPayrole();
                    $payrole_model->employee_id = $employee->EmployeeId;
                    $payrole_model->month_days = $month_days;
                    $payrole_model->holidays = count($holidays);
                    $payrole_model->weekend_days = $weekend;
                    $payrole_model->working_days = $total_working_days;
                    $payrole_model->present_days = $emp_present_days; //$present_days->present_days;
                    $payrole_model->absent_days = $absent;
                    $payrole_model->late_minute = $late_min;
                    $payrole_model->previous_gatepassmin = $employee_leave_left->gate_pass_min;
                    $payrole_model->gatepassmin = $gtm;
                    $payrole_model->gt_full_day = $fullday;
                    $payrole_model->gt_half_day = $halfday;
                    $payrole_model->overtime_min = $overtime_min;
                    $payrole_model->total_gatepass = $total_gatepass;
                    $payrole_model->late_count = $late_count;
                    $payrole_model->lwp = $lwp;
                    $payrole_model->paid_leave = $paid_leave;
                    $payrole_model->salary = $employee->salary;
                    $payrole_model->gross_salary = $grosssal;
                    $payrole_model->total_pf = $total_pf;
                    $payrole_model->total_esic = $total_esic;
                    $payrole_model->total_deduction = $total_deduction;
                    $payrole_model->payout = number_format((float)$payout, 2, '.', '');
                    $payrole_model->date = $month . ',' . $year;
                    $payrole_model->session_id = $session_master->id;
                    $payrole_model->created_time = Carbon::now('Asia/Kolkata');
                    $payrole_model->save();

                }
            }
            return redirect('create-payroll')->with('message', 'Temporary Payroll has been generated');
        } else {
            return Redirect::back()->with('errmessage', 'Please select any employee');
        }
    }

    public function convert_payroll(Request $request, $payroll_date)
    {

        $payroll_date = base64_decode($payroll_date);
        $arrayDate = explode(",", $payroll_date);
//        $temp_payrole_model = TempPayrole::find($temp_id);
        $payrolls = TempPayrole::where(['date' => $payroll_date])->get();

        foreach ($payrolls as $temp_payrole_model) {
            $parole_check = Payrole::where(['date' => $payroll_date, 'employee_id' => $temp_payrole_model->employee_id])->first();
            if (!isset($parole_check)) {
                $payrole_model = new Payrole();
                $payrole_model->employee_id = $temp_payrole_model->employee_id;
                $payrole_model->month_days = $temp_payrole_model->month_days;
                $payrole_model->holidays = $temp_payrole_model->holidays;
                $payrole_model->weekend_days = $temp_payrole_model->weekend_days;
                $payrole_model->working_days = $temp_payrole_model->working_days;
                $payrole_model->present_days = $temp_payrole_model->present_days;
                $payrole_model->absent_days = $temp_payrole_model->absent_days;
                $payrole_model->late_minute = $temp_payrole_model->late_minute;
                $payrole_model->previous_gatepassmin = $temp_payrole_model->previous_gatepassmin;
                $payrole_model->gatepassmin = $temp_payrole_model->gatepassmin;
                $payrole_model->overtime_min = $temp_payrole_model->overtime_min;
                $payrole_model->total_gatepass = $temp_payrole_model->total_gatepass;
                $payrole_model->late_count = $temp_payrole_model->late_count;
                $payrole_model->lwp = $temp_payrole_model->lwp;
                $payrole_model->cl_taken = $temp_payrole_model->cl;
                $payrole_model->ml_taken = $temp_payrole_model->ml;
                $payrole_model->paid_leave = $temp_payrole_model->paid_leave;
                $payrole_model->salary = $temp_payrole_model->salary;
                $payrole_model->gross_salary = $temp_payrole_model->gross_salary;
                $payrole_model->total_pf = $temp_payrole_model->total_pf;
                $payrole_model->total_esic = $temp_payrole_model->total_esic;
                $payrole_model->total_deduction = $temp_payrole_model->total_deduction;
                $payrole_model->payout = $temp_payrole_model->payout;
                $payrole_model->date = $temp_payrole_model->date;
                $payrole_model->session_id = $temp_payrole_model->session_id;
                $payrole_model->created_time = Carbon::now('Asia/Kolkata');
                $payrole_model->save();

                $dayMin = ($temp_payrole_model->gt_full_day * 360) + ($temp_payrole_model->gt_half_day * 180);
                $gatemint = $temp_payrole_model->previous_gatepassmin + $temp_payrole_model->gatepassmin;
                $finalGt = $gatemint - $dayMin;

                $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $temp_payrole_model->employee_id, 'session_id' => $temp_payrole_model->session_id])->first();
                $employee_leave_left->gate_pass_min = 0;
                $employee_leave_left->save();
                $employee_leave_left->gate_pass_min = $finalGt;
                $employee_leave_left->cl -= $temp_payrole_model->cl;
                $employee_leave_left->ml -= $temp_payrole_model->ml;
                $employee_leave_left->save();
//            echo $employee_leave_left->gate_pass_min."<br>";

                $gatepass = DB::select("SELECT * FROM `temp_gate_pass` WHERE employee_id = $temp_payrole_model->employee_id and MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
                if (count($gatepass) > 0) {
                    foreach ($gatepass as $gatepas) {
                        $gate = new GatePass();
                        $gate->employee_id = $gatepas->employee_id;
                        $gate->late_min = $gatepas->late_min;
                        $gate->date = $gatepas->date; //date_format(date_create($gatepas->date), "Y-m-d");
                        $gate->session_id = $gatepas->session_id;
                        $gate->save();
//                    if (isset($employee_leave_left)) {
//                        $employee_leave_left->gate_pass_min += $gatepas->late_min;
//                        $employee_leave_left->save();
//                    }
                    }
                }

                $temp_leaves = DB::select("SELECT * FROM `temp_leave` WHERE employee_id = $temp_payrole_model->employee_id and MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");

                if (count($temp_leaves) > 0) {
                    foreach ($temp_leaves as $temp_leave) {
                        $leave_model = new EmployeeLeaves();
                        $leave_model->date = $temp_leave->date;
                        $leave_model->employee_id = $temp_leave->employee_id;
                        $leave_model->session_id = $temp_leave->session_id;
                        $leave_model->leave_type = $temp_leave->leave_type;
                        $leave_model->save();
//                    if (isset($employee_leave_left) && $temp_leave->leave_type == 'CL') {
//                        $employee_leave_left->cl -= 1;
//                        $employee_leave_left->save();
//                    } else if (isset($employee_leave_left) && $temp_leave->leave_type == 'ML') {
//                        $employee_leave_left->ml -= 1;
//                        $employee_leave_left->save();
//                    }
                    }
                }

                $temp_overtimes = DB::select("SELECT * FROM `temp_overtime` WHERE employee_id = $temp_payrole_model->employee_id and MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");

                if (count($temp_overtimes) > 0) {
                    foreach ($temp_overtimes as $temp_overtime) {
                        $overtime = new Overtime();
                        $overtime->employee_id = $temp_overtime->employee_id;
                        $overtime->overtime_min = $temp_overtime->overtime_min;;
                        $overtime->date = $temp_overtime->date;
                        $overtime->session_id = $temp_overtime->session_id;
                        $overtime->save();
                    }
                }
            }

            DB::select("DELETE FROM `temp_gate_pass` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
            DB::select("DELETE FROM `temp_leave` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
            DB::select("DELETE FROM `temp_overtime` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
            TempPayrole::where(['date' => $payroll_date])->delete();
        }

        return redirect('create-payroll')->with('message', 'Payroll has been generated');
    }

    public function delete_payroll_temp()
    {
        $arrayDate = explode(",", request('date'));
        DB::select("DELETE FROM `temp_gate_pass` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
        DB::select("DELETE FROM `temp_leave` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
        DB::select("DELETE FROM `temp_overtime` WHERE MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
        TempPayrole::where(['date' => request('date')])->delete();
        return redirect('create-payroll')->with('message', 'Temporary Payroll has been deleted');
    }

}


//ALTER TABLE `employees` ADD `school_id` INT NULL DEFAULT '1' AFTER `is_active`;
//ALTER TABLE `employees` ADD `is_pf_applied` TINYINT NOT NULL DEFAULT '0' AFTER `salary`;


//ALTER TABLE `temp_payrole` ADD `previous_gatepassmin` INT NULL DEFAULT NULL AFTER `late_minute`;
//ALTER TABLE `payrole` ADD `previous_gatepassmin` INT NULL DEFAULT NULL AFTER `late_minute`
//ALTER TABLE `temp_payrole` ADD `gt_full_day` INT NULL DEFAULT '0' AFTER `gatepassmin`, ADD `gt_half_day` INT NULL DEFAULT '0' AFTER `gt_full_day`;