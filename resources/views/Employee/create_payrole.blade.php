@extends('master.master')
@section('title','L.K.S.S.S. | GPayroll Payrole')
@section('content')
    <link rel="stylesheet" href="{{ url('css/checkbox.css') }}">

    <style>
        .mybg {
            padding: 10px 10px;
        }


    </style>

    <div class="container-fluid page-body-wrapper" id="maindiv">
        <div class="main-panel">
            <div class="content-wrapper">


                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Generate Payroll</h4>
                        <hr>
                        <form class="forms-sample" action="{{ url('generate_payroll') }}" method="get">
                            <div class="row">
                                <div class="col-sm-3">

                                    <div class="form-group">
                                        @php
                                            $employeelist = \App\EmployeeModel::where(['is_active'=>1])->get();
                                        @endphp
                                        <label for="exampleInputEmail3">Employee List</label>
                                        <select size="1" name="" class="form-control typeDD" id="employee_id"
                                                style="width: 100%;">
                                            <option selected value="0">Select Any One</option>
                                            @foreach($employeelist as $employee)
                                                <option value="{{$employee->EmployeeId}}">{{$employee->EmployeeName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputName1">Month</label>
                                        <select size="1" id="month_name" name="month" class="form-control">
                                            <option selected value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Year</label>
                                        @php
                                            $already_selected_value = 2019;
                                            $earliest_year = 2001;
                                            print '<select name="year" id="year_name" class="form-control">';
                                                foreach (range(date('Y'), $earliest_year) as $x) {
                                                print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                                                }
                                                print '</select>';
                                        @endphp
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="exampleInputEmail3"></label><br>
                                    <button type="button"
                                            {{--onclick="blockPage();"--}} onclick="getTempPayrollEmployee()"
                                            class="btn btn-warning mr-2">Get Employee List
                                    </button>
                                </div>
                            </div>
                            <div id="EmpList">

                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Temporary Payroll List</h4>
                        <hr>
                        <table class=" table table-bordered" id="example1">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Month</th>
                                <th class="border-bottom-0" style="color:white;">Year</th>
                                <th class="border-bottom-0" style="color:white;">Total Payroll</th>
                                <th class="border-bottom-0" style="color:white;">Total PF (Rs.)</th>
                                <th class="border-bottom-0" style="color:white;">Total ESIC(Rs.)</th>
                                <th class="border-bottom-0" style="color:white;">Total Payout</th>
                                <th class="border-bottom-0" style="color:white;">Generated Date</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($temp_payroles) > 0)
                                @foreach ($temp_payroles as $index => $payrole)
                                    @php

                                        $array  = explode(",", $payrole->date);
                                    $total_pf = \App\TempPayrole::where(['date'=>$payrole->date])->sum('total_pf');
                                    $total_esic = \App\TempPayrole::where(['date'=>$payrole->date])->sum('total_esic');
                                    $total_payout = \App\TempPayrole::where(['date'=>$payrole->date])->sum('payout');
                                    $modified_count = \App\TempPayrole::where(['date'=>$payrole->date,'is_modified'=>1])->count();
                                    $total_payout = number_format("$total_payout",2,".",",");
                                    $total_pf = number_format("$total_pf",2,".",",");
                                    $total_esic = number_format("$total_esic",2,".",",");

                                    @endphp
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $array[0], 10)) }}</td>
                                        <td>{{ $array[1]}}</td>
                                        <td>{{ $payrole->payrole_generated }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_pf }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_esic}}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_payout }}</td>
                                        <td>{{date_format(date_create($payrole->created_time), "d-M-Y h:i A")}}</td>
                                        <td>

                                            {{--<button type="button" onclick="del_payroll('{{$payrole->date}}')"--}}
                                            {{--class="btn btn-sm btn-danger ">Delete--}}
                                            {{--</button>--}}
                                            {{--&nbsp;--}}
                                            {{--<button type="button" onclick="del_payroll('{{$payrole->date}}')"--}}
                                            {{--class="btn btn-sm btn-success ">Mark as locked--}}
                                            {{--</button>--}}
                                            <div class="dropdown btn-sm">
                                                <button type="button" class="btn btn-success btn-xs dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    Option
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                       href="{{url('view-temp-payroll').'/'.base64_encode($payrole->date)}}">View
                                                        Temp Payrolls</a>
                                                    <a class="dropdown-item"
                                                       {{-- href="{{url('delete_payroll_temp?date=').$payrole->date}}"--}} onclick="del_payroll('{{$payrole->date}}');"
                                                       href="#">Delete Temp Payroll</a>
                                                    {{--                                                    @if($payrole->payrole_generated == $modified_count)--}}
                                                    <a class="dropdown-item"
                                                       onclick="convert_payroll('{{base64_encode($payrole->date)}}')"
                                                       href="{{--{{url('convert_payroll').'/'.base64_encode($payrole->date)}}--}}#">Mark
                                                        as locked</a>
                                                    {{--@endif--}}
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="8"> < No Record Found ></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Generated Payroll List</h4>
                        <hr>
                        <table class=" table table-bordered" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Month</th>
                                <th class="border-bottom-0" style="color:white;">Year</th>
                                <th class="border-bottom-0" style="color:white;">Total Payroll</th>
                                <th class="border-bottom-0" style="color:white;">Total PF (Rs.)</th>
                                <th class="border-bottom-0" style="color:white;">Total ESIC(Rs.)</th>
                                <th class="border-bottom-0" style="color:white;">Total Payout</th>
                                <th class="border-bottom-0" style="color:white;">Generated Date</th>
                                <th class="border-bottom-0" style="color:white; width: 100px !important;">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($payroles) > 0)
                                @foreach ($payroles as $index => $payrole)
                                    @php
                                        $array  = explode(",", $payrole->date);
                                    $total_pf = \App\Payrole::where(['date'=>$payrole->date])->sum('total_pf');
                                    $total_esic = \App\Payrole::where(['date'=>$payrole->date])->sum('total_esic');
                                    $total_payout = \App\Payrole::where(['date'=>$payrole->date])->sum('payout');
                                    @endphp
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $array[0], 10)) }}</td>
                                        <td>{{ $array[1]}}</td>
                                        <td>{{ $payrole->payrole_generated }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_pf }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_esic}}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_payout }}</td>
                                        <td>{{date_format(date_create($payrole->created_time), "d-M-Y h:i A")}}</td>
                                        <td>
                                            <div class="dropdown btn-sm">
                                                <button type="button" class="btn btn-success btn-xs dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    Option
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                       href="{{url('view-payroll').'/'.base64_encode($payrole->date)}}">View
                                                        Payroll</a>
                                                </div>
                                            </div>

                                            {{--<button type="button" onclick="del_payroll('{{$payrole->date}}')"  class="btn btn-sm btn-danger ">Delete</button>--}}

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="8"> < No Record Found ></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function () {
            getTempPayrollEmployee();
        });
        function getTempPayrollEmployee() {
            var editurl = '{{ url('getTempPayrollEmployee') }}';
            var month_name = $('#month_name').val();
            var year_name = $('#year_name').val();
            var employee_id = $('#employee_id').val();
            $.ajax({
                type: "GET",
                contentType: "application/json; charset=utf-8",
                url: editurl,
                data: {date: month_name + ',' + year_name, employee_id: employee_id},
                success: function (data) {
                    $('#EmpList').html(data);
                },
                error: function (xhr, status, error) {
                    $('#mb').html(xhr.responseText);
                    //$('.modal-body').html("Technical Error Occured!");
                }
            });
        }


        function del_payroll(e_id) {
            {{--swal({--}}
                {{--title: "Are you sure?",--}}
                {{--text: "Once deleted, you will not be able to recover",--}}
                {{--icon: "warning",--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonClass: "btn-danger",--}}
                {{--cancelButtonText: "No, cancel plx!",--}}
                {{--closeOnConfirm: false,--}}
                {{--closeOnCancel: false,--}}
                {{--buttons: true,--}}
                {{--dangerMode: true,--}}
            {{--}).then((willDelete) => {--}}
                {{--if (willDelete) {--}}
                    {{--$.get('{{ url('delete_payroll_temp') }}', {date: e_id}, function (data) {--}}
                        {{--success_noti("Payroll has been deleted");--}}
                        {{--setTimeout(function () {--}}
                            {{--window.location.reload();--}}
                        {{--}, 1000);--}}
                    {{--});--}}

                {{--}--}}
            {{--}--}}
        {{--)--}}
            {{--;--}}
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                        if (willDelete) {
                            $.get('{{ url('delete_payroll_temp') }}', {date: e_id}, function (data) {
                                success_noti("Temp Payroll has been deleted");
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            });

                        }
                    }
            );

        }

        function convert_payroll(e_id) {
            swal({
                title: "Are you sure?",
                text: "you want to generate payroll",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                        if (willDelete) {
                            window.location.href = '{{url('convert_payroll').'/'}}' + e_id;
                            blockPage();
                            {{--$.get('{{ url('convert_payroll') }}', {date: e_id}, function (data) {--}}
                            {{--success_noti("Payroll has been generated");--}}
                            {{--setTimeout(function () {--}}
                            {{--window.location.reload();--}}
                            {{--}, 1000);--}}
                            {{--});--}}

                        }
                    }
            )
            ;

        }

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