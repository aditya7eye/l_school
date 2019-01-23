@extends('master.master')
@section('title','L.K.S.S.S. | GPayroll Payrole')
@section('content')
    <style>
        .mybg {
            padding: 10px 10px;
        }
    </style>

    <div class="container-fluid page-body-wrapper" id="maindiv">
        <div class="main-panel">
            <div class="content-wrapper">


                {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                {{--<h4 class="card-title">Generate Payrole</h4>--}}
                {{--<hr>--}}
                {{--<form class="forms-sample" action="{{ url('generate_payrole') }}" method="get">--}}
                {{--<div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputName1">Month</label>--}}
                {{--<select size="1" name="month" class="form-control">--}}
                {{--<option selected value="01">January</option>--}}
                {{--<option value="02">February</option>--}}
                {{--<option value="03">March</option>--}}
                {{--<option value="04">April</option>--}}
                {{--<option value="05">May</option>--}}
                {{--<option value="06">June</option>--}}
                {{--<option value="07">July</option>--}}
                {{--<option value="08">August</option>--}}
                {{--<option value="09">September</option>--}}
                {{--<option value="10">October</option>--}}
                {{--<option value="11">November</option>--}}
                {{--<option value="12">December</option>--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputEmail3">Year</label>--}}
                {{--@php--}}
                {{--$already_selected_value = 2019;--}}
                {{--$earliest_year = 2001;--}}
                {{--print '<select name="year" class="form-control">';--}}
                {{--foreach (range(date('Y'), $earliest_year) as $x) {--}}
                {{--print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';--}}
                {{--}--}}
                {{--print '</select>';--}}
                {{--@endphp--}}
                {{--</div>--}}

                {{--<button type="submit" class="btn btn-warning mr-2">Submit</button>--}}
                {{--</div>--}}
                {{--</form>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<br>--}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@if(count($payroles)>0)
                                @php
                                    $array  = explode(",", $date);
                                @endphp
                                {{date('F', mktime(0, 0, 0, $array[0], 10)).", ".$array[1]  }} @endif Payroll List <a href="javascript:history.back()" class="pull-right btn btn-xs btn-success">Go
                                Back</a></h4>
                        <hr>
                        <table class="center-aligned-table table table-responsive table-bordered"
                               style="height: 500px; overflow: scroll;" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Month/Year</th>
                                <th class="border-bottom-0" style="color:white;">Employee Name</th>
                                <th class="border-bottom-0" style="color:white;">Month Days</th>
                                <th class="border-bottom-0" style="color:white;">Weekend Days</th>
                                <th class="border-bottom-0" style="color:white;">holidays</th>
                                <th class="border-bottom-0" style="color:white;">Working Days</th>
                                <th class="border-bottom-0" style="color:white;">Present Days</th>
                                <th class="border-bottom-0" style="color:white;">Absent Days</th>
                                <th class="border-bottom-0" style="color:white;">Late Count</th>
                                <th class="border-bottom-0" style="color:white;">Late Coming Minute</th>
                                <th class="border-bottom-0" style="color:white;">Gatepass Minute</th>
                                <th class="border-bottom-0" style="color:white;">Leave Without Pay</th>
                                <th class="border-bottom-0" style="color:white;">Overtime Minute</th>
                                <th class="border-bottom-0" style="color:white;">Paid Leave</th>
                                <th class="border-bottom-0" style="color:white;">Salary</th>
                                <th class="border-bottom-0" style="color:white;">Gross Salary</th>
                                <th class="border-bottom-0" style="color:white;">Total PF</th>
                                <th class="border-bottom-0" style="color:white;">Total ESIC</th>
                                <th class="border-bottom-0" style="color:white;">Total Deduction</th>
                                <th class="border-bottom-0" style="color:white;">Payout</th>

                            </tr>
                            </thead>

                            <tbody>
                            @if(count($payroles) > 0)
                                @foreach ($payroles as $index => $payrole)
                                    <tr>
                                        <td>{{ $payrole->date }}</td>
                                        <td>{{ $payrole->employee->EmployeeName }}</td>
                                        <td>{{ $payrole->month_days }}</td>
                                        <td>{{ $payrole->weekend_days }}</td>
                                        <td>{{ $payrole->holidays }}</td>
                                        <td>{{ $payrole->working_days }}</td>
                                        <td>{{ $payrole->present_days }}</td>
                                        <td>{{ $payrole->absent_days }}</td>
                                        <td>{{ $payrole->late_count }}</td>
                                        <td>{{ $payrole->late_minute }}</td>
                                        <td>{{ $payrole->gatepassmin }}</td>
                                        <td>{{ $payrole->lwp }}</td>
                                        <td>{{ $payrole->overtime_min }}</td>
                                        <td>{{ $payrole->paid_leave }}</td>
                                        <td>{{ $payrole->salary }}</td>
                                        <td>{{ $payrole->gross_salary }}</td>
                                        <td>{{ $payrole->total_pf }}</td>
                                        <td>{{ $payrole->total_esic }}</td>
                                        <td>{{ $payrole->total_deduction }}</td>
                                        <td>{{ $payrole->payout }}</td>
                                        {{--<td>--}}
                                        {{--<button onclick="update_admin({{ $adminlistobj->id }})"--}}
                                        {{--class="btn btn-primary ">Edit--}}
                                        {{--</button>--}}
                                        {{--<button onclick="del_admin({{ $adminlistobj->id }});"--}}
                                        {{--class="btn btn-danger ">Delete--}}
                                        {{--</button>--}}
                                        {{--</td>--}}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="5">No Record Found</td>

                                </tr>
                            @endif


                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script>
        $(window).scroll(function () {
            var headerBottom = '.navbar.horizontal-layout .nav-bottom';
            if ($(window).scrollTop() >= 70) {
                $(headerBottom).addClass('fixed-top');
            } else {
                $(headerBottom).removeClass('fixed-top');
            }
        });
    </script>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

    <!-- partial -->




@stop