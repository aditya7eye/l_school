<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Payroll_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['content'] = 'payroll/payroll_list';
        $this->load->view('common/master', $data);
    }

    public function ajax_list()
    {
        $list = $this->Payroll_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $payroll) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = anchor(site_url('payroll/payslip/' . $payroll->payroll_id), $this->User_model->get_by_id($payroll->user_id)->name, 'target="_blank"');
            $attendance_arr = json_decode($payroll->attendance);
            foreach ($attendance_arr as $value) {
                if ($value->type == 'Month Days' || $value->type == 'Total Paid Days') {
                    $row[] = $value->amount;
                }
            }
            $fixed_arr = json_decode($payroll->salarypart);
            foreach ($fixed_arr as $fixed_row) {
                if ($fixed_row->type == 'FIXED BASIC' || $fixed_row->type == 'FIXED DEARNESS ALLOWANCE' || $fixed_row->type == 'FIXED HOUSE RENT ALLOWANCE' || $fixed_row->type == 'FIXED CONVEYANCE' || $fixed_row->type == 'FIXED SPECIAL ALLOWANCE' || $fixed_row->type == 'FIXED MEDICAL ALLOWANCE' || $fixed_row->type == 'FIXED OTHER ALLOWANCE') {
                    $row[] = $fixed_row->amount;
                }
            }
            $salary_earned_arr = json_decode($payroll->salary_earned);
            foreach ($salary_earned_arr as $salary_earned_row) {
                if ($salary_earned_row->type == 'EARNED BASIC' || $salary_earned_row->type == 'EARNED DEARNESS ALLOWANCE' || $salary_earned_row->type == 'EARNED HOUSE RENT ALLOWANCE' || $salary_earned_row->type == 'EARNED CONVEYANCE' || $salary_earned_row->type == 'EARNED SPECIAL ALLOWANCE' || $salary_earned_row->type == 'EARNED MEDICAL ALLOWANCE' || $salary_earned_row->type == 'EARNED OTHER ALLOWANCE') {
                    $row[] = $salary_earned_row->amount;
                }
            }
            $row[] = $payroll->grosssal;
            $salary_deduction_arr = json_decode($payroll->deductions);
            foreach ($salary_deduction_arr as $salary_deduction_row) {
                if ($salary_deduction_row->type == 'EMPLOYEE ESI' || $salary_deduction_row->type == 'PROVIDENT FUND' || $salary_deduction_row->type == 'LABOUR WELFARE FUND' || $salary_deduction_row->type == 'PROFESSIONAL TAX') {
                    $row[] = $salary_deduction_row->amount;
                }
            }
            $row[] = $payroll->total_allowance;
            $row[] = $payroll->total_arrear;
            $row[] = $payroll->total_deduction;
            $row[] = $payroll->employer_salary;
            $row[] = $payroll->payout;
            $eployer_salarypart_arr = json_decode($payroll->eployer_salarypart);
            foreach ($eployer_salarypart_arr as $eployer_salarypart_row) {
                if ($eployer_salarypart_row->type == 'EMPLOYER ESI' || $eployer_salarypart_row->type == 'EMPLOYER PROVIDENT FUND' || $eployer_salarypart_row->type == 'EMPLOYER LABOUR WELFARE FUND' || $eployer_salarypart_row->type == 'INSURANCE') {

                    $row[] = $eployer_salarypart_row->amount;
                }
            }
            $row[] = $payroll->ctc;
            $row[] = $payroll->date;
            //$row[] = anchor(site_url('payroll/payslip/'.$payroll->payroll_id),'<i class="fa fa-eye"></i>','target="_blank"');
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Payroll_model->count_all(),
            "recordsFiltered" => $this->Payroll_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function payslip($id)
    {

        $data['param2'] = $id;
        $data['content'] = 'payroll/payroll_details_new';
        $this->load->view('common/master', $data);

    }

    public function payslip2($id)
    {

        $data['param2'] = $id;
//        $data['content'] = 'test_1';
        $this->load->view('test_1', $data);

    }

    function payroll_report_view()
    {
        $client_id = $this->input->post('client');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $employee = $this->db->order_by('user_id', 'ASC')->get_where('user', array('type' => 2, 'client_id' => $client_id))->result_array();
        $client = $this->db->get_where('client', array('client_id' => $client_id))->row();
        //$row_value[]="No.";
        // emp info
        $i = 0;
        //print_r($employee );die;
        foreach ($employee as $row) {

            $payroll = $this->db->get_where('payroll', array('date' => $month . ',' . $year, 'user_id' => $row['user_id']))->row_array();
            //s$data['pay']=$payroll;
            $bank = $this->db->get_where('bank', array('bank_id' => $row['bank_id']))->row_array();
            if (!empty($payroll)) {
                //$row_value[]=$i;
                $row_value[] = $client->client_name;
                $row_value[] = $client->client_ccode;
                $row_value[] = $row['emp_code'];
                $row_value[] = $row['user_regcode'];
                $row_value[] = $row['name'];
                $row_value[] = ($row['date_of_joining'] != 0) ? date("d-M-Y", $row['date_of_joining']) : '';
                $row_value[] = $row['pan_no'];
                $row_value[] = $row['uid_no'];
                $row_value[] = $row['un_no'];
                $row_value[] = $row['ip_no'];
                $row_value[] = $row['gender'];
                $row_value[] = ($row['status'] == 1) ? 'Active' : 'Inactive';
                $row_value[] = $row['phone'];
                $city = '';
                if ($row['city'] != 0)
                    $city = $this->db->get_where('city', array('id' => $row['city']))->row()->city_name;
                $row_value[] = $city;

                $state = '';
                if ($row['state'] != 0)
                    $state = $this->db->get_where('state', array('id' => $row['state']))->row()->state_name;
                $row_value[] = $state;

                $designation = '';
                if ($row['designation_id'] != 0)
                    $designation = $this->db->get_where('designation', array('designation_id' => $row['designation_id']))->row()->name;
                $row_value[] = $designation;
                // ******************************************************************
                $attendance_arr = json_decode($payroll['attendance']);
                foreach ($attendance_arr as $value) {
                    if ($value->type == 'Month Days' || $value->type == 'Total Paid Days') {
                        $row_value[] = $value->amount;
                    }
                }
                //  ******************************************************************
                $row_value[] = $row['joining_salary'];

                $fixed_arr = json_decode($payroll['salarypart']);
                foreach ($fixed_arr as $fixed_row) {
                    if ($fixed_row->type == 'FIXED BASIC' || $fixed_row->type == 'FIXED DEARNESS ALLOWANCE' || $fixed_row->type == 'FIXED HOUSE RENT ALLOWANCE' || $fixed_row->type == 'FIXED CONVEYANCE' || $fixed_row->type == 'FIXED SPECIAL ALLOWANCE' || $fixed_row->type == 'FIXED MEDICAL ALLOWANCE' || $fixed_row->type == 'FIXED OTHER ALLOWANCE') {
                        $row_value[] = $fixed_row->amount;
                    }
                }
                $row_value[] = $bank['account_holder_name'];
                $row_value[] = $bank['name'];
                $row_value[] = $bank['account_number'];
                $row_value[] = $bank['ifsc_code'];
                $row_value[] = $payroll['grosssal'];
                $salary_earned_arr = json_decode($payroll['salary_earned']);
                foreach ($salary_earned_arr as $salary_earned_row) {
                    if ($salary_earned_row->type == 'EARNED BASIC' || $salary_earned_row->type == 'EARNED DEARNESS ALLOWANCE' || $salary_earned_row->type == 'EARNED HOUSE RENT ALLOWANCE' || $salary_earned_row->type == 'EARNED CONVEYANCE' || $salary_earned_row->type == 'EARNED SPECIAL ALLOWANCE' || $salary_earned_row->type == 'EARNED MEDICAL ALLOWANCE' || $salary_earned_row->type == 'EARNED OTHER ALLOWANCE') {
                        $row_value[] = $salary_earned_row->amount;
                    }
                }
                $salary_deduction_arr = json_decode($payroll['deductions']);
                foreach ($salary_deduction_arr as $salary_deduction_row) {
                    if ($salary_deduction_row->type == 'EMPLOYEE ESI' || $salary_deduction_row->type == 'PROVIDENT FUND' || $salary_deduction_row->type == 'LABOUR WELFARE FUND' || $salary_deduction_row->type == 'PROFESSIONAL TAX' || $salary_deduction_row->type == 'OTHER DEDUCTION INCOME TAX' || $salary_deduction_row->type == 'SALARY ADVANCE DEDUCTION' || $salary_deduction_row->type == 'INSURANCE DEDUCTION' || $salary_deduction_row->type == 'TAX DEDUCTION') {
                        $row_value[] = $salary_deduction_row->amount;
                    }

                }

                $eployer_salarypart_arr = json_decode($payroll['eployer_salarypart']);
                foreach ($eployer_salarypart_arr as $eployer_salarypart_row) {
                    if ($eployer_salarypart_row->type == 'EMPLOYER ESI' || $eployer_salarypart_row->type == 'EPS WAGES' || $eployer_salarypart_row->type == 'EMPLOYER PROVIDENT FUND' || $eployer_salarypart_row->type == 'EMPLOYER LABOUR WELFARE FUND' || $eployer_salarypart_row->type == 'INSURANCE') {

                        $row_value[] = $eployer_salarypart_row->amount;
                    }
                }
                $row_value[] = $payroll['grosssal'];
                $row_value[] = $payroll['total_allowance'];
                $row_value[] = $payroll['total_arrear'];
                $row_value[] = $payroll['total_deduction'];
                $row_value[] = $payroll['payout'];
                $row_value[] = $payroll['employer_salary'];
                $row_value[] = $payroll['ctc'];
                $form_array[] = $row_value;
                $row_value = array();
            }


            $i++;
        }

        if (!empty($form_array)) {
            $countarray = count($form_array) + 1;

            require_once APPPATH . 'third_party/PHPExcel/Bootstrap.php';
            // require_once base_url().'application/third_party/PHPExcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('Webeasystep.com ')
                ->setLastModifiedBy('Rahul lodhi')
                ->setTitle('Phpecxel codeigniter tutorial')
                ->setSubject('integrate codeigniter with PhpExcel')
                ->setDescription('this is the file test');

            // add style to the header
            $styleArray = array(
                'borders' => array(
                    'vertical' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
            );

            $styleArray1 = array(
                'font' => array(
                    'bold' => true,
                    'type' => 'text',
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );

            $styleArray2 = array(
                'font' => array(
                    'bold' => true,
                    'type' => 'text',
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '419641',
                    ),
                    'endcolor' => array(
                        'argb' => '419641',
                    ),
                ),
            );

            $styleArray3 = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'eca1a6',
                    ),
                    'endcolor' => array(
                        'argb' => 'eca1a6',
                    ),
                ),
            );

            $styleArray4 = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'DEB887',
                    ),
                    'endcolor' => array(
                        'argb' => 'DEB887',
                    ),
                ),
            );

            $styleArray5 = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '00FFFF',
                    ),
                    'endcolor' => array(
                        'argb' => '00FFFF',
                    ),
                ),
            );


            $styleArray6 = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '8FBC8F',
                    ),
                    'endcolor' => array(
                        'argb' => '8FBC8F',
                    ),
                ),
            );

            $styleArray7 = array(
                'font' => array(
                    'bold' => true,
                    'type' => 'text',
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA07A',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFA07A',
                    ),
                ),
            );

            $spreadsheet->getActiveSheet()->freezePane('F2');
            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
            $spreadsheet->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray1);
            $spreadsheet->getActiveSheet()->getStyle('Q1:X1')->applyFromArray($styleArray2);
            $spreadsheet->getActiveSheet()->getStyle('Y1:AB1')->applyFromArray($styleArray3);
            $spreadsheet->getActiveSheet()->getStyle('AC1:AJ1')->applyFromArray($styleArray4);
            $spreadsheet->getActiveSheet()->getStyle('AK1:AR1')->applyFromArray($styleArray5);
            $spreadsheet->getActiveSheet()->getStyle('AS1:BA1')->applyFromArray($styleArray6);
            $spreadsheet->getActiveSheet()->getStyle('BB1:BF1')->applyFromArray($styleArray7);
//        $spreadsheet->getActiveSheet()->getStyle('A1:BF1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getDefaultStyle()->applyFromArray($styleArray);

            // Auto size columns for each worksheet
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));
                $sheet = $spreadsheet->getActiveSheet();
                $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(true);
                /** @var PHPExcel_Cell $cell */
                foreach ($cellIterator as $cell) {
                    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                }
            }

            // Auto size columns for each worksheet************End

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client')
                ->setCellValue("B1", 'Client_Code')
                ->setCellValue("C1", 'SaiSun_Emp_Code')
                ->setCellValue("D1", 'SaiSun_system code')
                ->setCellValue("E1", 'Employee_Name')
                ->setCellValue("F1", 'Date of Joining')
                ->setCellValue("G1", 'PAN')
                ->setCellValue("H1", 'Aadhar')
                ->setCellValue("I1", 'UAN')
                ->setCellValue("J1", 'ESIC Number')
                ->setCellValue("K1", 'Gender')
                ->setCellValue("L1", 'Status')
                ->setCellValue("M1", 'Contact')
                ->setCellValue("N1", 'Location')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Designation')
                ->setCellValue("Q1", 'Month Days')
                ->setCellValue("R1", 'Paid Days')
                ->setCellValue("S1", 'Fixed Gross')
                ->setCellValue("T1", 'Fixed_Basic')
                ->setCellValue("U1", 'Fixed_DA')
                ->setCellValue("V1", 'Fixed_HRA')
                ->setCellValue("W1", 'Fixed_Conveyance')
                ->setCellValue("X1", 'Fixed_Special Allowance')//0-24
                ->setCellValue("Y1", 'Fixed_Medical Allowance')//0-22 CORRECT
                ->setCellValue("Z1", 'Fixed_Other Allowance')//35
                ->setCellValue("AA1", 'Account Holder Name')
                ->setCellValue("AB1", 'Bank Name')
                ->setCellValue("AC1", 'Bank Account Number')
                ->setCellValue("AD1", 'IFS CODE')
                ->setCellValue("AE1", 'Earned Gross')
                ->setCellValue("AF1", 'Earned_Basic')
                ->setCellValue("AG1", 'Earned_DA')
                ->setCellValue("AH1", 'Earned_HRA')
                ->setCellValue("AI1", 'Earned_Conveyance')
                ->setCellValue("AJ1", 'Earned_Special Allowance')//45
                ->setCellValue("AK1", 'Earned_Medical Allowance')//48
                ->setCellValue("AL1", 'Earned_Other Allowance')//59
                ->setCellValue("AM1", 'EMPLOYEE ESI')
                ->setCellValue("AN1", 'PROVIDENT FUND')
                ->setCellValue("AO1", 'LABOUR WELFARE FUND')
                ->setCellValue("AP1", 'PROFESSIONAL TAX')
                ->setCellValue("AQ1", 'OTHER DEDUCTION INCOME TAX')
                ->setCellValue("AR1", 'SALARY ADVANCE DEDUCTION')//65
                ->setCellValue("AS1", 'INSURANCE DEDUCTION')//68
                ->setCellValue("AT1", 'TAX DEDUCTION')//72
                ->setCellValue("AU1", 'EMPLOYER ESI')
                ->setCellValue("AV1", 'EPS WAGES')
//                ->setCellValue("AW1", 'PF ADMIN CHARGES')
//                ->setCellValue("AX1", 'EDLI')
//                ->setCellValue("AY1", 'EMPLOYEE PENSION SCHEME')
                ->setCellValue("AW1", 'EMPLOYER PROVIDENT FUND')
//                ->setCellValue("BA1", 'INSURANCE ADMIN CHARGES')
                ->setCellValue("AX1", 'EMPLOYER LABOUR WELFARE FUND')
                ->setCellValue("AY1", 'INSURANCE')
                ->setCellValue("AZ1", 'Earn Salary')
                ->setCellValue("BA1", 'Total Allowance')
                ->setCellValue("BB1", 'Total Arrear')
                ->setCellValue("BC1", 'Total Deduction')
                ->setCellValue("BD1", 'Pay Out')
                ->setCellValue("BE1", 'Employer Salary')
                ->setCellValue("BF1", 'Total CTC');


            $spreadsheet->getActiveSheet()->getStyle('H2:H' . $countarray)
                ->getNumberFormat()
                ->setFormatCode(
                    '00000000000'
                );

            $spreadsheet->getActiveSheet()->getStyle('I2:I' . $countarray)
                ->getNumberFormat()
                ->setFormatCode(
                    '00000000000'
                );
            $spreadsheet->getActiveSheet()->getStyle('J2:J' . $countarray)
                ->getNumberFormat()
                ->setFormatCode(
                    '00000000000'
                );
            $spreadsheet->getActiveSheet()->getStyle('AC2:AC' . $countarray)
                ->getNumberFormat()
                ->setFormatCode(
                    '00000000000'
                );

            $x = 2;
            foreach ($form_array as $sub) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $sub['0'])
                    ->setCellValue("B$x", $sub['1'])
                    ->setCellValue("C$x", $sub['2'])
                    ->setCellValue("D$x", $sub['3'])
                    ->setCellValue("E$x", $sub['4'])
                    ->setCellValue("F$x", $sub['5'])
                    ->setCellValue("G$x", $sub['6'])
                    ->setCellValue("H$x", $sub['7'])
                    ->setCellValue("I$x", $sub['8'])
                    ->setCellValue("J$x", $sub['9'])
                    ->setCellValue("K$x", $sub['10'])
                    ->setCellValue("L$x", $sub['11'])
                    ->setCellValue("M$x", $sub['12'])
                    ->setCellValue("N$x", $sub['13'])
                    ->setCellValue("O$x", $sub['14'])
                    ->setCellValue("P$x", $sub['15'])
                    ->setCellValue("Q$x", $sub['16'])
                    ->setCellValue("R$x", $sub['17'])
                    ->setCellValue("S$x", $sub['18'])
                    ->setCellValue("T$x", $sub['19'])
                    ->setCellValue("U$x", $sub['20'])
                    ->setCellValue("V$x", $sub['21'])//0-21
                    ->setCellValue("W$x", $sub['22'])
                    ->setCellValue("X$x", $sub['23'])
                    ->setCellValue("Y$x", $sub['24'])
//                    ->setCellValue("Z$x", $sub['25'])
//                    ->setCellValue("AA$x", $sub['26'])
//                    ->setCellValue("AB$x", $sub['27'])
//                    ->setCellValue("AC$x", $sub['28'])
//                    ->setCellValue("AD$x", $sub['29'])
//                    ->setCellValue("AE$x", $sub['30'])
//                    ->setCellValue("AF$x", $sub['31'])
//                    ->setCellValue("AG$x", $sub['32'])
//                    ->setCellValue("AH$x", $sub['33'])
//                    ->setCellValue("AI$x", $sub['34'])
                    ->setCellValue("Z$x", $sub['25'])//35
                    ->setCellValue("AA$x", $sub['26'])
                    ->setCellValue("AB$x", $sub['27'])
                    ->setCellValue("AC$x", $sub['28'])
                    ->setCellValue("AD$x", $sub['29'])
                    ->setCellValue("AE$x", $sub['30'])
                    ->setCellValue("AF$x", $sub['31'])
                    ->setCellValue("AG$x", $sub['32'])
                    ->setCellValue("AH$x", $sub['33'])
                    ->setCellValue("AI$x", $sub['34'])
                    ->setCellValue("AJ$x", $sub['35'])//45 SPECIAL
//                    ->setCellValue("AU$x", $sub['46'])
//                    ->setCellValue("AV$x", $sub['47'])
                    ->setCellValue("AK$x", $sub['36'])//48=Earned_Medical Allowance
//                    ->setCellValue("AX$x", $sub['49'])
//                    ->setCellValue("AY$x", $sub['50'])
//                    ->setCellValue("AZ$x", $sub['51'])
//                    ->setCellValue("BA$x", $sub['52'])
//                    ->setCellValue("BB$x", $sub['53'])
//                    ->setCellValue("BC$x", $sub['54'])
//                    ->setCellValue("BD$x", $sub['55'])
//                    ->setCellValue("BE$x", $sub['56'])
//                    ->setCellValue("BF$x", $sub['57'])
//                    ->setCellValue("BG$x", $sub['58'])
                    ->setCellValue("AL$x", $sub['37'])
                    ->setCellValue("AM$x", $sub['38'])
                    ->setCellValue("AN$x", $sub['39'])
                    ->setCellValue("AO$x", $sub['40'])
                    ->setCellValue("AP$x", $sub['41'])
                    ->setCellValue("AQ$x", $sub['42'])
                    ->setCellValue("AR$x", $sub['43'])
//                    ->setCellValue("AQ$x", $sub['66'])
//                    ->setCellValue("AR$x", $sub['67'])
                    ->setCellValue("AS$x", $sub['44'])
//                    ->setCellValue("AR$x", $sub['69'])
//                    ->setCellValue("AS$x", $sub['70'])
//                    ->setCellValue("AT$x", $sub['71'])

                    ->setCellValue("AT$x", $sub['45'])
                    ->setCellValue("AU$x", $sub['46'])
                    ->setCellValue("AV$x", $sub['47'])
                    ->setCellValue("AW$x", $sub['48'])
                    ->setCellValue("AX$x", $sub['49'])
                    ->setCellValue("AY$x", $sub['50'])
                    ->setCellValue("AZ$x", $sub['51'])
                    ->setCellValue("BA$x", $sub['52'])
                    ->setCellValue("BB$x", $sub['53'])
                    ->setCellValue("BC$x", $sub['54'])
                    ->setCellValue("BD$x", $sub['55'])
                    ->setCellValue("BE$x", $sub['56'])
                    ->setCellValue("BF$x", $sub['57']);
                $x++;
            }

// Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Users Information');

// set right to left direction
//      $spreadsheet->getActiveSheet()->setRightToLeft(true);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $spreadsheet->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="payroll_report.xlsx"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            //$this->session->set_flashdata('message', 'Payroll Download Successfully');
            exit;
        } else {
            $this->session->set_flashdata('message_error', 'Record Not Found');

        }

        redirect(base_url() . 'payroll', 'refresh');
    }

    public function payroll_generate()
    {

        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $employee_list = $this->db->order_by('id', 'ASC')->get_where('employee', array('status' => 1))->result();
        $table = "devicelogs_" . $month . "_" . $year;
        //$employee_list= $this->db->order_by('EmployeeId')->get('employees')->result();

        $month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($i = 1; $i <= 1; $i++)
            $all_date_arr[] = $year . '-' . (($month < 10) ? '0' . $month : $month) . '-' . (($i < 10) ? '0' . $i : $i);

        $holidays = $this->db->query("SELECT * FROM `holiday` WHERE date BETWEEN '$year-$month-01' and '$year-$month-31'")->num_rows();//2;

        $weekend = $this->sunday_ina_month($month, $year);

        $total_working_days = $month_days - $holidays - $weekend;
        $employee_arr = array();
        $payroll_date = $month . "," . $year;
        $payrolls = $this->db->get_where('payroll', array('date' => $payroll_date))->result();
        if (empty($payrolls)) {
            if ($this->db->table_exists($table)) {

                foreach ($employee_list as $employee) {
                    if ($employee->date_of_joining <= $all_date_arr[0] && $employee->date_of_leaving >= $all_date_arr[0]) {


                        $late_min = 0;
                        $late_min1 = 0;
                        $late_min2 = 0;
                        $absent = 0;
                        $comming_days = 0;
                        $late_count = 0;
                        $UserId = $employee->emp_code;
                        $lwp = 0;
                        $paid_leave = 0;
                        $total_pf = 0;
                        $total_esic = 0;
                        $total_deduction = 0;

                        $grosssal = 0;
                        $payout = 0;
                        $emp_cat_row = $this->db->get_where('employee_cat', array('id' => $employee->emp_cat))->row();
                        $given_max_cl = $emp_cat_row->cl;
                        $given_max_ml = $emp_cat_row->ml;
                        $taken_cl = $this->db->get_where('leave', array('emp_id' => $employee->id, 'type' => 'CL', 'session_id' => $this->session->userdata('current_session_id')))->num_rows();
                        $taken_ml = $this->db->get_where('leave', array('emp_id' => $employee->id, 'type' => 'ML', 'session_id' => $this->session->userdata('current_session_id')))->num_rows();


                        $left_max_cl = $given_max_cl - $taken_cl;
                        $left_max_ml = $given_max_ml - $taken_ml;

                        $qry = "SELECT UserId as id, date_format(LogDate,'%Y-%m-%d') as date, MIN(LogDate) as colIn, MAX(LogDate) as colOut FROM $table where UserId='$UserId' group by date(LogDate)";
                        $query = $this->db->query($qry);
                        $comming_days = $query->num_rows();
                        $result = $query->result();
                        if ($comming_days > 0) {
                            //echo  $sql=$this->db->last_query(); die;

                            $absent = $total_working_days - $comming_days;

                            foreach ($result as $value) {
                                $employee_arr1 = array();
                                $cintime = $value->date . ' 10:00:00';
                                $couttime = $value->date . ' 19:00:00';
                                if ($value->colIn > $cintime) {
                                    $datetime1 = new DateTime($cintime);
                                    $datetime2 = new DateTime($value->colIn);
                                    $interval = $datetime1->diff($datetime2);
                                    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                    $hours = $interval->format('%h');
                                    $minutes = $interval->format('%i');
                                    $late_min1 += ($hours * 60 + $minutes);
                                    $late_count++;
                                }
                                if ($value->colOut < $couttime) {
                                    $datetime11 = new DateTime($couttime);
                                    $datetime21 = new DateTime($value->colOut);
                                    $interval1 = $datetime11->diff($datetime21);
                                    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
                                    $hours1 = $interval1->format('%h');
                                    $minutes1 = $interval1->format('%i');
                                    $late_min2 += ($hours1 * 60 + $minutes1);
                                }
                                $pdate_arr[] = $value->date;
                            }

                            $late_min = $late_min1 + $late_min2;


                        } else {
                            continue;
                        }
                        /*print_r($pdate_arr);
                        echo '<br>';
                        print_r($all_date_arr);
                        $rleave=0;
                        for($j=0;$j<count($all_date_arr);$j++){
                        if(!in_array($all_date_arr[$j],$pdate_arr)){
                        $rleave++;

                        }

                        }*/
                        //echo 'ateek'. $rleave;
                        $employee_arr1['user_id'] = $UserId;
                        $employee_arr1['total_days'] = $month_days;
                        $employee_arr1['Holidays'] = $holidays;
                        $employee_arr1['Weekend_days'] = $weekend;
                        $employee_arr1['working_days'] = $total_working_days;
                        $employee_arr1['present_days'] = $comming_days;
                        $employee_arr1['absent_days'] = $absent;
                        $employee_arr1['late_minute'] = $late_min;
                        $employee_arr1['late_count'] = $late_count;
                        $employee_arr1['salary'] = $employee->salary;
                        $employee_arr[] = $employee_arr1;
                        /* one day salay*/

                        $oneday_sal = $employee->salary / $month_days;


                        $lwp = $absent;
                        if ($late_count > 2) {
                            if ($late_count == 3)
                                $lwp = $lwp + 1;
                            else
                                $lwp = $lwp + intval($late_count / 2);

                        }
                        if ($month != 5 && $month != 6) {
                            if (($left_max_cl > 0 || $left_max_ml > 0) && $lwp > 0) {

                                $data_leave['date'] = $month . ',' . $year;
                                $leave_obj = $this->db->get_where('leave', array('date' => $data_leave['date'], 'emp_id' => $employee->id, 'session_id' => $this->session->userdata('current_session_id')))->row();
                                //$query= $payroll ;

                                if (empty($leave_obj)) {

                                    $data_leave['date'] = $month . ',' . $year;
                                    $data_leave['emp_id'] = $employee->id;
                                    $data_leave['session_id'] = $this->session->userdata('current_session_id');
                                    $data_leave['type'] = 'CL';
                                    $this->db->insert('leave', $data_leave);
                                    $paid_leave++;
                                    $lwp--;
                                    $left_max_cl--;
                                    if ($left_max_cl > 0 && $lwp > 0) {
                                        $this->db->insert('leave', $data_leave);
                                        $paid_leave++;
                                        $lwp--;
                                        $left_max_cl--;
                                        if ($left_max_ml > 0 && $lwp > 0) {
                                            $data_leave['date'] = $month . ',' . $year;
                                            $data_leave['emp_id'] = $employee->id;
                                            $data_leave['session_id'] = $this->session->userdata('current_session_id');
                                            $data_leave['type'] = 'ML';
                                            $this->db->insert('leave', $data_leave);
                                            $paid_leave++;
                                            $lwp--;
                                            $left_max_ml--;
                                        }
                                        if ($left_max_ml > 0 && $lwp > 0) {
                                            $data_leave['date'] = $month . ',' . $year;
                                            $data_leave['emp_id'] = $employee->id;
                                            $data_leave['session_id'] = $this->session->userdata('current_session_id');
                                            $data_leave['type'] = 'ML';
                                            $this->db->insert('leave', $data_leave);
                                            $paid_leave++;
                                            $lwp--;
                                            $left_max_ml--;
                                        }

                                    }
                                }

                            }
                        }


                        $total_deduction = $oneday_sal * $lwp;
                        $total_deduction = round($total_deduction, 2);
                        $grosssal = $employee->salary - $total_deduction;
                        if ($employee->pf == 1) {
                            $total_pf = ($grosssal * 10) / 100;
                            $total_pf = round($total_pf, 2);
                            if ($total_pf > 2040)
                                $total_pf = 2040;
                            $total_esic = (($grosssal - $total_pf) * 1.75) / 100;
                            $total_esic = round($total_esic, 2);
                        }
                        $payout = $grosssal - $total_pf - $total_esic;

                        $total_deduction = $total_deduction + $total_pf + $total_esic;
                        //-------------
                        $attendance = array();

                        //echo $row->user_id;<br />

                        $new_entry = array('type' => 'Month Days', 'amount' => $month_days);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'Weekly-Off', 'amount' => $weekend);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'Holiday-Off', 'amount' => $holidays);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'Working Days', 'amount' => $total_working_days);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'LWP', 'amount' => $lwp);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'Present Days', 'amount' => $comming_days);

                        array_push($attendance, $new_entry);

                        $new_entry = array('type' => 'Paid Leaves', 'amount' => $paid_leave);

                        array_push($attendance, $new_entry);

                        //--------------------


                        $data['payroll_code'] = substr(md5(rand(10000000, 2000000000)), 0, 7);
                        $data['user_id'] = $employee->id;
                        $data['attendance'] = json_encode($attendance);
                        $data['grosssal'] = $grosssal;
                        $data['total_pf'] = $total_pf;
                        $data['total_esic'] = $total_esic;
                        $data['total_deduction'] = $total_deduction;
                        $data['payout'] = number_format((float)$payout, 2, '.', '');
                        $data['date'] = $month . ',' . $year;
                        $data['session_id'] = $this->session->userdata('current_session_id');
                        $data['status'] = 1;
                        //print_r($data);
                        $payroll = $this->db->get_where('payroll', array('date' => $data['date'], 'user_id' => $employee->id))->row();
                        //$query= $payroll ;

                        if (!empty($payroll)) {

                            $this->db->where('payroll_id', $payroll->payroll_id);
                            $this->db->update('payroll', $data);
                        } else
                            $this->db->insert('payroll', $data);

                    }
                }

                if (!empty($employee_arr)) {
                    $this->session->set_flashdata('message', 'Generated Successfully');


                } else {
                    $this->session->set_flashdata('message_error', 'Record Not Found');
                }
            } else {
                $this->session->set_flashdata('message_error', 'Record Not Found');
            }
        } else {
            $this->session->set_flashdata('message_error', 'Payroll Exist!');
        }

        redirect(base_url() . 'payroll', 'refresh');
    }

    public function payrolllist()
    {

        $year = ($this->input->post('year')) ? $this->input->post('year') : date('Y');
        $month = $this->input->post('month');
        if ($month != '') {

            $payroll = $this->db->get_where('payroll', array('date' => $month . ',' . $year))->result_array();
            //$this->session->set_flashdata('message_error', 'Record Found');
            if (empty($payroll)) {
                //$this->session->set_flashdata('message_error', 'Record Not Found');
            }

            $data['pay'] = $payroll;
        }

        $data['month'] = $month;
        $data['year'] = $year;
        $data['content'] = 'payroll/payroll_list_view';
        $this->load->view('common/master', $data);
    }

    public function payslip_pdf($id)
    {
        $data['param2'] = $id;
//        $data['content'] = 'payroll/payroll_details_new';
//        $data[] = $id;
        //load the view and saved it into $html variable
        $html = $this->load->view('payroll/payroll_details_new', $data, true);

        //this the the PDF filename that user will get to download
        $pdfFilePath = "output_pdf_name.pdf";

        //load mPDF library
        $this->load->library('m_pdf');

        //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    public function paylist()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'category/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'category/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'category/index.html';
            $config['first_url'] = base_url() . 'category/index.html';
        }
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Category_model->total_rows($q);
        $category = $this->Category_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data = array(
            'category_data' => $category,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $data['content'] = 'category/category_list';
        $this->load->view('common/master', $data);

    }

    public function employee_payslip($id)
    {

        $list = $this->Payroll_model->get_by_id($id);
//        print_r($list);
        $data['payroll'] = $list;
        $data['payroll_id'] = $id;
        $data['content'] = 'payroll/payroll_edit_form';
        $this->load->view('common/master', $data);

    }

    public function edit_payroll()
    {
        $payroll_id = $this->input->post('payroll_id');
        $salarypart = array();
        $salpart_types = $this->input->post('salary_earned_title');
        $salpart_amounts = $this->input->post('salary_earned_amount');
        $number_of_entries = sizeof($salpart_types);
        $total_earn = 0;
        for ($i = 0; $i < $number_of_entries;) {

            if ($salpart_types[$i] != "") {
                $new_entry = array('type' => $salpart_types[$i], 'amount' => $salpart_amounts[$i]);
                array_push($salarypart, $new_entry);
                $total_earn += $salpart_amounts[$i];
            }
            $i++;
        }
        $earned = json_encode($salarypart);
        $arrear_part = array();
        $arrear_types = $this->input->post('arrear_title');
        $arrear_amounts = $this->input->post('arrear_amount');
        $number_of_entries = sizeof($arrear_types);
        $total_arrear = 0;
        for ($i = 0; $i < $number_of_entries;) {

            if ($arrear_types[$i] != "") {

                $new_entry = array('type' => $arrear_types[$i], 'amount' => $arrear_amounts[$i]);

                array_push($arrear_part, $new_entry);
                $total_arrear += $arrear_amounts[$i];
            }
            $i++;
        }
        $arrear = json_encode($arrear_part);
        $allowance_part = array();
        $allowance_types = $this->input->post('allow_title');

        $allowance_amounts = $this->input->post('allow_amount');
        $number_of_entries = sizeof($allowance_types);
        $total_allowance = 0;
        for ($i = 0; $i < $number_of_entries;) {

            if ($allowance_types[$i] != "") {

                $new_entry = array('type' => $allowance_types[$i], 'amount' => $allowance_amounts[$i]);

                array_push($allowance_part, $new_entry);
                $total_allowance += $allowance_amounts[$i];
            }
            $i++;
        }
        $allowance = json_encode($allowance_part);
        $deduction_part = array();
        $deduction_types = $this->input->post('deduct_title');
        $deduction_amounts = $this->input->post('deduct_amount');
        $number_of_entries = sizeof($deduction_types);
        $total_deduction = 0;
        for ($i = 0; $i < $number_of_entries;) {

            if ($deduction_types[$i] != "") {

                $new_entry = array('type' => $deduction_types[$i], 'amount' => $deduction_amounts[$i]);

                array_push($deduction_part, $new_entry);
                $total_deduction += $deduction_amounts[$i];
            }
            $i++;
        }
        $deduction = json_encode($deduction_part);
        $empr_salary_part = array();
        $empr_salary_types = $this->input->post('empr_salary_title');
        $empr_salary_amounts = $this->input->post('empr_salary_amount');
        $number_of_entries = sizeof($empr_salary_types);
        $total_empr_salary = 0;
        for ($i = 0; $i < $number_of_entries;) {

            if ($empr_salary_types[$i] != "") {

                $new_entry = array('type' => $empr_salary_types[$i], 'amount' => $empr_salary_amounts[$i]);

                array_push($empr_salary_part, $new_entry);
                $total_empr_salary += $empr_salary_amounts[$i];
            }
            $i++;
        }
        $empr_salary = json_encode($empr_salary_part);
        $total_earned_gross = $total_earn + $total_arrear;
        $net_gross = $total_earned_gross - $total_deduction;
        $net_pay = $net_gross + $total_allowance;
        $ctc = $total_earned_gross + $total_empr_salary;

        $data = array(

            'grosssal' => $total_earned_gross,
            'salary_earned' => $earned,
            'total_allowance' => $total_allowance,
            'total_arrear' => $total_arrear,
            'total_deduction' => $total_deduction,
            'employer_salary' => $total_empr_salary,
            'payout' => $net_pay,
            'ctc' => $ctc,
            'allowances' => $allowance,
            'deductions' => $deduction,
            'salary_earned' => $earned,
            'arrears' => $arrear,
            'eployer_salarypart' => $empr_salary,
        );

        $this->Payroll_model->update($payroll_id, $data);
        echo "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
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

    public function excel($month = 0, $year = 0)
    {

        $this->load->helper('exportexcel');
        $namaFile = $month . '_' . $year . "_Payroll.xls";
        $judul = "Payroll";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Employee");
        xlsWriteLabel($tablehead, $kolomhead++, "Gross Salary");
        xlsWriteLabel($tablehead, $kolomhead++, "PF");
        xlsWriteLabel($tablehead, $kolomhead++, "ESIC");
        xlsWriteLabel($tablehead, $kolomhead++, "Total Deduction");
        xlsWriteLabel($tablehead, $kolomhead++, "Payout");
        xlsWriteLabel($tablehead, $kolomhead++, "Date");

        $payroll = $this->db->get_where('payroll', array('date' => $month . ',' . $year))->result();
        foreach ($payroll as $data) {
            $kolombody = 0;
            $employee_name = $this->db->get_where('employee', array('id' => $data->user_id))->row()->name;
            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $employee_name);
            xlsWriteNumber($tablebody, $kolombody++, $data->grosssal);
            xlsWriteNumber($tablebody, $kolombody++, $data->total_pf);
            xlsWriteNumber($tablebody, $kolombody++, $data->total_esic);
            xlsWriteNumber($tablebody, $kolombody++, $data->total_deduction);
            xlsWriteNumber($tablebody, $kolombody++, $data->payout);
            xlsWriteLabel($tablebody, $kolombody++, $data->date);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Lwf.php */
/* Location: ./application/controllers/Lwf.php */
/* Please DO NOT modify this information : */
/* Generated on Codeigniter2018-05-01 09:27:36 */
