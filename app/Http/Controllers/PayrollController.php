<?php

namespace App\Http\Controllers;

use App\EmployeeLeaves;
use App\EmployeeModel;
use App\EmployeeType;
use App\ErrorLog;
use App\Payrole;
use App\SessionMaster;
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
        $payroles = \Illuminate\Support\Facades\DB::select("SELECT DISTINCT(payrole.date), COUNT(id) as payrole_generated, created_time  FROM `payrole` WHERE 1 GROUP by payrole.date ORDER by payrole.date desc");
        return view('employee.create_payrole')->with(['payroles' => $payroles]);
    }

    public function payrole_list($date)
    {
        try {
            $date = base64_decode($date);
            $payroles = Payrole::where(['date' => $date])->get();
            return view('employee.employee_payroll_list')->with(['payroles' => $payroles, 'date' => $date]);
        } catch (Exception $e) {
            ErrorLog::store_error($e->getMessage(), 'PayrollController', 'payrole_list');
            return view('error.404');
            //return Redirect::back()->withErrors('Something went wrong');
        }
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
                $absent = 0;
                $late_count = 0;
                $UserId = $employee->emp_code;
                $lwp = 0;
                $paid_leave = 0;
                $total_pf = 0;
                $total_esic = 0;
                $total_deduction = 0;

                $grosssal = 0;
                $payout = 0;
                $emp_cat_row = EmployeeType::where(['id' => $employee->type_id])->first();

                $given_max_cl = 12;//$emp_cat_row->cl;

                $given_max_ml = 7;//$emp_cat_row->ml;

                $taken_cl = EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'CL', 'session_id' => $session_master->id])->count();
                $taken_ml = EmployeeLeaves::where(['employee_id' => $employee->id, 'leave_type' => 'ML', 'session_id' => $session_master->id])->count();


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

                        foreach ($attendance_records as $value) {
                            $employee_arr1 = array();
                            $cintime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->school->opening_time;
                            $couttime = date_format(date_create($value->AttendanceDate), "Y-m-d") . ' ' . $employee->school->closing_time;
                            if ($value->InTime > $cintime) {
                                $datetime1 = new DateTime($cintime);
                                $datetime2 = new DateTime($value->InTime);
                                $interval = $datetime1->diff($datetime2);
                                //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                $hours = $interval->format('%h');
                                $minutes = $interval->format('%i');
                                $late_min1 += ($hours * 60 + $minutes);
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
                            }
//                        $pdate_arr[] = $value->AttendanceDate;
                        }
                        $late_min = $late_min1 + $late_min2;
                    }


                } else {
//                echo "dsa";
//                    continue;
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
                                    $leave_model->session_id = $session_master->session;
                                    $leave_model->leave_type = 'ML';
                                    $leave_model->save();
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
                                    $leave_model->session_id = $session_master->session;
                                    $leave_model->leave_type = 'ML';
                                    $leave_model->save();

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
                $total_deduction = $oneday_sal * $lwp;
                $total_deduction = round($total_deduction, 2);
                $grosssal = $employee->salary - $total_deduction;
                if ($employee->is_pf_applied == 1) {
                    $total_pf = ($grosssal * 10) / 100;
                    $total_pf = round($total_pf, 2);
                    if ($total_pf > 2040)
                        $total_pf = 2040;
                    $total_esic = (($grosssal - $total_pf) * 1.75) / 100;
                    $total_esic = round($total_esic, 2);
                }
                $payout = $grosssal - $total_pf - $total_esic;

                $total_deduction = $total_deduction + $total_pf + $total_esic;
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
            return redirect('create-payrole')->with('message', 'Payroll has been generated');
        } else {
            return Redirect::back()->with('message', 'Payroll already generated for selected date');
        }
    }

    public function delete_payroll()
    {
        Payrole::where(['date' => request('date')])->delete();
    }

}


//ALTER TABLE `employees` ADD `school_id` INT NULL DEFAULT '1' AFTER `is_active`;
//ALTER TABLE `employees` ADD `is_pf_applied` TINYINT NOT NULL DEFAULT '0' AFTER `salary`;