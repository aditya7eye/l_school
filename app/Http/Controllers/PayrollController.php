<?php

namespace App\Http\Controllers;

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
        $year = $request->input('year');
        $month = $request->input('month');
        $pdate_check = $month . "," . $year;
        $parole_check = TempPayrole::where(['date' => $pdate_check])->first();
        if (!isset($parole_check)) {
            $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $employee_list = EmployeeModel::where(['is_active' => 1])->get();

            for ($i = 1; $i <= 1; $i++)
                $all_date_arr[] = $year . '-' . (($month < 10) ? '0' . $month : $month) . '-' . (($i < 10) ? '0' . $i : $i);

            $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31'");//2;

            $weekend = $this->sunday_ina_month($month, $year);
            $total_working_days = $month_days - count($holidays) - $weekend;
            $session_master = SessionMaster::where(['is_active' => 1])->first();

            foreach ($employee_list as $employee) {
//            if ($employee->DOJ <= $all_date_arr[0] && $employee->date_of_leaving >= $all_date_arr[0]) {


                $late_min = 0;
                $late_min1 = 0;
                $late_min2 = 0;
                $overtime_min = 0;
                $gtm = 0;
                $gatepassmin = 0;
                $absent = 0;
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


                $taken_cl = $given_max_cl - $employee_leave_left->cl;//EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'CL', 'session_id' => $session_master->id])->count();
                $taken_ml = $given_max_ml - $employee_leave_left->ml;//EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'ML', 'session_id' => $session_master->id])->count();


                $left_max_cl = $given_max_cl - $taken_cl;
                $left_max_ml = $given_max_ml - $taken_ml;

                $present_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as present_days FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");

//            $absent_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as absent_days FROM `attendancelogs` WHERE StatusCode = 'A' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");

                $attendance_records = DB::select("SELECT * FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");


//            $qry = "SELECT UserId as id, date_format(LogDate,'%Y-%m-%d') as date, MIN(LogDate) as colIn, MAX(LogDate) as colOut FROM $table where UserId='$UserId' group by date(LogDate)";
//            $query = $this->db->query($qry);
//            $comming_days = $query->num_rows();
//            $result = $query->result();

                /*********************Late Min/Count Calculation***************************/
                if ($present_days->present_days > 0) {
                    //echo  $sql=$this->db->last_query(); die;

                    $absent = $total_working_days - $present_days->present_days;

                    if (count($attendance_records) > 0) {
                        $PFESIC = PFESIC::find(1);
                        foreach ($attendance_records as $value) {
                            $employee_arr1 = array();
                            $cintime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->check_in;
                            $couttime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->check_out;
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
//                                    if (isset($employee_leave_left)) {
//                                        $employee_leave_left->gate_pass_min += $lmt;
//                                        $employee_leave_left->save();
//                                    }
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
//                        $pdate_arr[] = $value->AttendanceDate;
                        }
                        $late_min = $late_min1 + $late_min2;
                        $gatepassmin += $gtm;
                    }


                }
                /*********************Late Min/Count Calculation***************************/

                /*********************Lwp(Leave Without Pay) Calculation***************************/
                $oneday_sal = $employee->salary / $month_days;  ///Salary Per Day
//                $lwp = $absent;
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
                $fullday = 0;
                $halfday = 0;
                $minCal = $employee_leave_left->gate_pass_min + $gatepassmin;
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

//                echo $td."<br>";
//                echo $total_deduction."<br>";
//                echo $total_gatepass."<br>";
//                echo $payout."<br>";
//                dd($td."<br>".$total_deduction."<br>".$total_gatepass."<br>".$payout."<br>");
                /*********************Gross Salary Deduction Calculation***************************/

                $payrole_model = new TempPayrole();
                $payrole_model->employee_id = $employee->EmployeeId;
                $payrole_model->month_days = $month_days;
                $payrole_model->holidays = count($holidays);
                $payrole_model->weekend_days = $weekend;
                $payrole_model->working_days = $total_working_days;
                $payrole_model->present_days = $present_days->present_days;
                $payrole_model->absent_days = $absent;
                $payrole_model->late_minute = $late_min;
                $payrole_model->gatepassmin = $gatepassmin;
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
            return redirect('create-payroll')->with('message', 'Temporary Payroll has been generated');
        } else {
            return Redirect::back()->with('errmessage', 'Temporary Payroll already generated for selected date');
        }
    }

    public function convert_payroll(Request $request, $payroll_date)
    {

        $payroll_date = base64_decode($payroll_date);
        $arrayDate = explode(",", $payroll_date);
//        $temp_payrole_model = TempPayrole::find($temp_id);
        $payrolls = TempPayrole::where(['date' => $payroll_date])->get();

        foreach ($payrolls as $temp_payrole_model) {
            $payrole_model = new Payrole();
            $payrole_model->employee_id = $temp_payrole_model->employee_id;
            $payrole_model->month_days = $temp_payrole_model->month_days;
            $payrole_model->holidays = $temp_payrole_model->holidays;
            $payrole_model->weekend_days = $temp_payrole_model->weekend_days;
            $payrole_model->working_days = $temp_payrole_model->working_days;
            $payrole_model->present_days = $temp_payrole_model->present_days;
            $payrole_model->absent_days = $temp_payrole_model->absent_days;
            $payrole_model->late_minute = $temp_payrole_model->late_minute;
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

            $employee_leave_left = EmployeeLeaveLeft::where(['employee_id' => $temp_payrole_model->employee_id, 'session_id' => $temp_payrole_model->session_id])->first();

            $employee_leave_left->gate_pass_min = $temp_payrole_model->gatepassmin;
            $employee_leave_left->cl -= $temp_payrole_model->cl;
            $employee_leave_left->ml -= $temp_payrole_model->ml;
            $employee_leave_left->save();

            $gatepass = DB::select("SELECT * FROM `temp_gate_pass` WHERE employee_id = $temp_payrole_model->employee_id and MONTH(date) = $arrayDate[0] AND YEAR(date) = $arrayDate[1]");
            if (count($gatepass) > 0) {
                foreach ($gatepass as $gatepas) {
                    $gate = new GatePass();
                    $gate->employee_id = $gatepas->employee_id;
                    $gate->late_min = $gatepas->late_min;
                    $gate->date = $gatepas->date; //date_format(date_create($gatepas->date), "Y-m-d");
                    $gate->session_id = $gatepas->session_id;
                    $gate->save();
                    if (isset($employee_leave_left)) {
                        $employee_leave_left->gate_pass_min += $gatepas->late_min;
                        $employee_leave_left->save();
                    }
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
                    if (isset($employee_leave_left) && $temp_leave->leave_type == 'CL') {
                        $employee_leave_left->cl -= 1;
                        $employee_leave_left->save();
                    } else if (isset($employee_leave_left) && $temp_leave->leave_type == 'ML') {
                        $employee_leave_left->ml -= 1;
                        $employee_leave_left->save();
                    }
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

        return redirect('create-payroll')->with('message', 'Payroll has been generated');
    }

    public function generate_payrole_final(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $pdate_check = $month . "," . $year;
        $parole_check = Payrole::where(['date' => $pdate_check])->first();
        if (!isset($parole_check)) {
            $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $employee_list = EmployeeModel::where(['is_active' => 1])->get();

            for ($i = 1; $i <= 1; $i++)
                $all_date_arr[] = $year . '-' . (($month < 10) ? '0' . $month : $month) . '-' . (($i < 10) ? '0' . $i : $i);

            $holidays = DB::select("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31'");//2;

            $weekend = $this->sunday_ina_month($month, $year);
            $total_working_days = $month_days - count($holidays) - $weekend;
            $session_master = SessionMaster::where(['is_active' => 1])->first();

            foreach ($employee_list as $employee) {
//            if ($employee->DOJ <= $all_date_arr[0] && $employee->date_of_leaving >= $all_date_arr[0]) {


                $late_min = 0;
                $late_min1 = 0;
                $late_min2 = 0;
                $overtime_min = 0;
                $gatepassmin = 0;
                $absent = 0;
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


                $taken_cl = $given_max_cl - $employee_leave_left->cl;//EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'CL', 'session_id' => $session_master->id])->count();
                $taken_ml = $given_max_ml - $employee_leave_left->ml;//EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'ML', 'session_id' => $session_master->id])->count();


                $left_max_cl = $given_max_cl - $taken_cl;
                $left_max_ml = $given_max_ml - $taken_ml;

                $present_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as present_days FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");

//            $absent_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as absent_days FROM `attendancelogs` WHERE StatusCode = 'A' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");

                $attendance_records = DB::select("SELECT * FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = $month AND YEAR(AttendanceDate) = $year");


//            $qry = "SELECT UserId as id, date_format(LogDate,'%Y-%m-%d') as date, MIN(LogDate) as colIn, MAX(LogDate) as colOut FROM $table where UserId='$UserId' group by date(LogDate)";
//            $query = $this->db->query($qry);
//            $comming_days = $query->num_rows();
//            $result = $query->result();

                /*********************Late Min/Count Calculation***************************/
                if ($present_days->present_days > 0) {
                    //echo  $sql=$this->db->last_query(); die;

                    $absent = $total_working_days - $present_days->present_days;

                    if (count($attendance_records) > 0) {
                        $PFESIC = PFESIC::find(1);
                        foreach ($attendance_records as $value) {
                            $employee_arr1 = array();
                            $cintime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->check_in;
                            $couttime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->check_out;
                            if ($value->InTime > $cintime) {
                                $datetime1 = new DateTime($cintime);
                                $datetime2 = new DateTime($value->InTime);
                                $interval = $datetime1->diff($datetime2);
                                //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                $hours = $interval->format('%h');
                                $minutes = $interval->format('%i');
                                $lmt = ($hours * 60 + $minutes);
                                if ($lmt > $PFESIC->gate_pass_min) {
                                    $gatepass = new GatePass();
                                    $gatepass->employee_id = $employee->EmployeeId;
                                    $gatepass->late_min = $lmt;
                                    $gatepass->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                    $gatepass->session_id = $session_master->id;
                                    $gatepass->save();
                                    $gatepassmin += $lmt;
                                    if (isset($employee_leave_left)) {
                                        $employee_leave_left->gate_pass_min += $lmt;
                                        $employee_leave_left->save();
                                    }
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
                            } else {
                                $datetime11 = new DateTime($value->OutTime);
                                $datetime21 = new DateTime($couttime);
                                $interval1 = $datetime11->diff($datetime21);
                                //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                $hours1 = $interval1->format('%h');
                                $minutes1 = $interval1->format('%i');
                                $overtime_min += ($hours1 * 60 + $minutes1);

                                $overtime = new Overtime();
                                $overtime->employee_id = $employee->EmployeeId;
                                $overtime->overtime_min = $overtime_min;
                                $overtime->date = date_format(date_create($value->AttendanceDate), "Y-m-d");
                                $overtime->session_id = $session_master->id;
                                $overtime->save();
                            }
//                        $pdate_arr[] = $value->AttendanceDate;
                        }
                        $late_min = $late_min1 + $late_min2;
                    }


                }
                /*********************Late Min/Count Calculation***************************/

                /*********************Lwp(Leave Without Pay) Calculation***************************/
                $oneday_sal = $employee->salary / $month_days;  ///Salary Per Day
                $lwp = $absent;
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

                            $leave_model = new EmployeeLeaves();
                            $leave_model->date = $month . ',' . $year;
                            $leave_model->employee_id = $employee->id;
                            $leave_model->session_id = $session_master->id;
                            $leave_model->leave_type = 'CL';
                            $leave_model->save();
                            if (isset($employee_leave_left)) {
                                $employee_leave_left->cl -= 1;
                                $employee_leave_left->save();
                            }
//                        $this->db->insert('leave', $data_leave);
                            $paid_leave++;
                            $lwp--;
                            $left_max_cl--;
                            if ($left_max_cl > 0 && $lwp > 0) {
                                $leave_model = new EmployeeLeaves();
                                $leave_model->date = $month . ',' . $year;
                                $leave_model->employee_id = $employee->id;
                                $leave_model->session_id = $session_master->id;
                                $leave_model->leave_type = 'CL';
                                $leave_model->save();
                                if (isset($employee_leave_left)) {
                                    $employee_leave_left->cl -= 1;
                                    $employee_leave_left->save();
                                }
                                $paid_leave++;
                                $lwp--;
                                $left_max_cl--;
                                if ($left_max_ml > 0 && $lwp > 0) {
                                    //$data_leave['date'] = $month . ',' . $year;
                                    //$data_leave['emp_id'] = $employee->id;
                                    //$data_leave['session_id'] = $session_master->session;
                                    //$data_leave['type'] = 'ML';
                                    //$this->db->insert('leave', $data_leave);

                                    $leave_model = new EmployeeLeaves();
                                    $leave_model->date = $month . ',' . $year;
                                    $leave_model->employee_id = $employee->id;
                                    $leave_model->session_id = $session_master->id;
                                    $leave_model->leave_type = 'ML';
                                    $leave_model->save();
                                    if (isset($employee_leave_left)) {
                                        $employee_leave_left->ml -= 1;
                                        $employee_leave_left->save();
                                    }
                                    $paid_leave++;
                                    $lwp--;
                                    $left_max_ml--;
                                }
                                if ($left_max_ml > 0 && $lwp > 0) {
                                    //$data_leave['date'] = $month . ',' . $year;
                                    //$data_leave['emp_id'] = $employee->id;
                                    //$data_leave['session_id'] = $session_master->session;
                                    //$data_leave['type'] = 'ML';
                                    //$this->db->insert('leave', $data_leave);
                                    $leave_model = new EmployeeLeaves();
                                    $leave_model->date = $month . ',' . $year;
                                    $leave_model->employee_id = $employee->id;
                                    $leave_model->session_id = $session_master->id;
                                    $leave_model->leave_type = 'ML';
                                    $leave_model->save();
                                    if (isset($employee_leave_left)) {
                                        $employee_leave_left->ml -= 1;
                                        $employee_leave_left->save();
                                    }

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
//                $gatepassHrs = $employee_leave_left->gate_pass_min->format('%h');
//                $gatepassminutes = 0;
//                $gatepasshours = floor($employee_leave_left->gate_pass_min / 60);
//                $gatepassminutes += $employee_leave_left->gate_pass_min % 60;

                $fullday = 0;
                $halfday = 0;
                $minCal = $employee_leave_left->gate_pass_min;
                while ($minCal > 179) {
                    if ($minCal > 360) {
                        $minCal = $minCal - 360;
                        $fullday++;
                    } elseif ($minCal > 180) {
                        $minCal = $minCal - 180;
                        $halfday++;
                    }
                }
                $employee_leave_left->gate_pass_min = $minCal;
                $employee_leave_left->save();
                $total_gatepass = $oneday_sal * $fullday + $halfday;

//
                $total_deduction = $oneday_sal * $lwp;
                $total_deduction = round($total_deduction, 2);
                $grosssal = $employee->salary - $total_deduction - $total_gatepass;
                if ($employee->is_pf_applied == 1) {
                    $pf_esic = PFESIC::find(1);
                    $total_pf = ($grosssal * $pf_esic->pf) / 100;
                    $total_pf = round($total_pf, 2);
                    if ($total_pf > 2040)
                        $total_pf = 2040;
                    $total_esic = (($grosssal - $total_pf) * $pf_esic->esic) / 100;
                    $total_esic = round($total_esic, 2);
                }
                $payout = $grosssal - $total_pf - $total_esic;

                $total_deduction = $total_deduction + $total_pf + $total_esic + $total_gatepass;
                /*********************Gross Salary Deduction Calculation***************************/

                $payrole_model = new Payrole();
                $payrole_model->employee_id = $employee->EmployeeId;
                $payrole_model->month_days = $month_days;
                $payrole_model->holidays = count($holidays);
                $payrole_model->weekend_days = $weekend;
                $payrole_model->working_days = $total_working_days;
                $payrole_model->present_days = $present_days->present_days;
                $payrole_model->absent_days = $absent;
                $payrole_model->late_minute = $late_min;
                $payrole_model->gatepassmin = $gatepassmin;
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
            return redirect('create-payroll')->with('message', 'Payroll has been generated');
        } else {
            return Redirect::back()->with('errmessage', 'Payroll already generated for selected date');
        }
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
