@extends('master.master')
@section('title','School | Attendance List')
@section('content')
    <style>
        .mybg {
            padding: 10px 10px;
        }

        #example2 > tbody > tr > td {
            padding: 2px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-align: center;
        }
    </style>

    <div class="container-fluid page-body-wrapper" id="maindiv">
        <div class="main-panel">
            <div class="content-wrapper">
                {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                {{--<h4 class="card-title">Create Plan</h4>--}}
                {{--<hr>--}}
                {{--<form class="forms-sample" action="{{ url('insertadmin') }}" method="get">--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputName1">Plan Name</label>--}}
                {{--<input type="text" class="form-control" id="p_name" name="p_name" placeholder="Plan Name">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputEmail3">Validity</label>--}}
                {{--<input type="text" class="form-control" name="validity" id="validity"  placeholder="Validity">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputEmail3">Type</label>--}}
                {{-- <input type="text" class="form-control" name="validity" id="validity"  placeholder="Validity"> --}}
                {{--<select name="type" id="type" class="form-control">--}}
                {{--<option value="Days">Days</option>--}}
                {{--<option value="Months">Months</option>--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputPassword4">Price</label>--}}
                {{--<input type="password" class="form-control" id="price" name="price" placeholder="Price">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                {{--<label for="exampleInputPassword4">Offer Price</label>--}}
                {{--<input type="password" class="form-control" id="o_price" name="o_price" placeholder="Offer Price">--}}
                {{--</div>--}}
                {{--<button type="submit" class="btn btn-warning mr-2">Submit</button>--}}
                {{--<a href="{{ url('add-admin') }}"><button type="button" class="btn btn-dark">Cancel</button></a>--}}

                {{--</form>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<br>--}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Attendance Report</h4>
                        <hr>
                        <form class="forms-sample" action="{{ url('getAttendance') }}" method="get">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputName1">Month</label>
                                        <select size="1" name="month" class="form-control">
                                            <option {{isset($month)?$month == '1' ? 'selected':'' : ''}} value="1">
                                                January
                                            </option>
                                            <option {{isset($month)?$month == '2' ? 'selected':'' : ''}} value="2">
                                                February
                                            </option>
                                            <option {{isset($month)?$month == '3' ? 'selected':'' : ''}} value="3">
                                                March
                                            </option>
                                            <option {{isset($month)?$month == '4' ? 'selected':'' : ''}} value="4">
                                                April
                                            </option>
                                            <option {{isset($month)?$month == '5' ? 'selected':'' : ''}} value="5">
                                                May
                                            </option>
                                            <option {{isset($month)?$month == '6' ? 'selected':'' : ''}} value="6">
                                                June
                                            </option>
                                            <option {{isset($month)?$month == '7' ? 'selected':'' : ''}} value="7">
                                                July
                                            </option>
                                            <option {{isset($month)?$month == '8' ? 'selected':'' : ''}} value="8">
                                                August
                                            </option>
                                            <option {{isset($month)?$month == '9' ? 'selected':'' : ''}} value="9">
                                                September
                                            </option>
                                            <option {{isset($month)?$month == '10' ? 'selected':'' : ''}} value="10">
                                                October
                                            </option>
                                            <option {{isset($month)?$month == '11' ? 'selected':'' : ''}} value="11">
                                                November
                                            </option>
                                            <option {{isset($month)?$month == '12' ? 'selected':'' : ''}} value="12">
                                                December
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Year</label>
                                        @php
                                            $already_selected_value = isset($year)?$year:"2019";
                                            $earliest_year = 2001;
                                            print '<select name="year" class="form-control">';
                                                foreach (range(date('Y'), $earliest_year) as $x) {
                                                print '<option value="'.$x.'"'.($x == $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                                                }
                                                print '</select>';
                                        @endphp
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <div class="form-group">
                                        @php
                                            $employeelist = \App\EmployeeModel::where(['is_active'=>1])->orderBy('EmployeeName','ASC')->get();
                                        @endphp
                                        <label for="exampleInputEmail3">Employee List</label>
                                        <select size="1" name="employee_id" class="typeDD">
                                            @foreach($employeelist as $employee)
                                                <option {{isset($employee_id)?$employee_id==$employee->EmployeeId?'selected':'':''}} value="{{$employee->EmployeeId}}">{{$employee->EmployeeName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="exampleInputEmail3"></label><br>
                                    <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        {{--<h4>Attendance Report</h4>--}}
                        {{--<hr>--}}
                        <table class="center-aligned-table table table-bordered" id="example2">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Employee Name</th>
                                <th class="border-bottom-0" style="color:white;">Attendance Date</th>
                                <th class="border-bottom-0" style="color:white;">Check In</th>
                                <th class="border-bottom-0" style="color:white;">Check Out</th>
                                <th class="border-bottom-0" style="color:white; width: 20%">Comment</th>
                                <th class="border-bottom-0" style="color:white;">Option</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $total_gatepass_min = 0;
                            @endphp
                            @if(isset($year))
                                @php
                                    $result= array();
                                        foreach (\App\Attendancelogs::getSundays($year, $month) as $sunday) {
                        $result[] = $sunday->format("d-M-Y");
                   }

                                @endphp
                            @endif
                            @if(count($attendance) > 0)
                                @foreach ($attendance as $index => $attendanc)
                                    @php
                                        $table = "devicelogs_" . $month . "_" . $year;
                                                        $empcode = $attendanc->employee->EmployeeCode;
                                        $attDate = date_format(date_create($attendanc->AttendanceDate), "Y-m-d");
                                        $devicelogs = \Illuminate\Support\Facades\DB::select("SELECT * FROM $table WHERE UserId = '$empcode' and LogDate like '%$attDate%' order by DeviceLogId desc");
                                        $total_gatepass = \Illuminate\Support\Facades\DB::selectOne("SELECT sum(C2) as total_gatepass FROM $table WHERE UserId = '$empcode' and LogDate like '%$attDate%' order by DeviceLogId desc");
                                    $att_date = date_format(date_create($attendanc->AttendanceDate), "d-M-Y");
                                    $att_date_h = date_format(date_create($attendanc->AttendanceDate), "Y-m-d");

                                    $holidays = DB::select("SELECT * FROM `holiday` WHERE date = '$att_date_h' and FIND_IN_SET('$attendanc->EmployeeId',employee_id) and is_active = 1");//2;

                                    @endphp
                                    <tr>
                                        <td>{{ isset($attendanc->employee->EmployeeName)?$attendanc->employee->EmployeeName :'' }}</td>
                                        <td>{{$att_date }}</td>
                                        <td>
                                            @if(count($holidays) == 0)
                                                @if(in_array("$att_date", $result))
                                                    @if($attendanc->InTime != '1900-01-01 00:00:00')
                                                        <span class="badge badge-success">Sunday({{date_format(date_create($attendanc->InTime), "d-M-Y h:i A")}}
                                                            )</span>
                                                    @else
                                                        <span class="badge badge-success">Sunday</span>
                                                    @endif

                                                @else
                                                    @if($attendanc->InTime != '1900-01-01 00:00:00')
                                                        {{date_format(date_create($attendanc->InTime), "d-M-Y h:i A")}}
                                                    @else
                                                        <span class="badge badge-danger">{{"Absent"}}</span>
                                                    @endif
                                                @endif
                                            @else
                                                @if(!in_array("$att_date", $result))
                                                    @if($attendanc->InTime != '1900-01-01 00:00:00')
                                                        <span class="badge badge-primary">{{"Holiday"}}
                                                            ({{date_format(date_create($attendanc->InTime), "d-M-Y h:i A")}}
                                                            )</span>
                                                    @else
                                                        <span class="badge badge-primary">{{"Holiday"}}</span>
                                                    @endif

                                                @else
                                                    @if($attendanc->InTime != '1900-01-01 00:00:00')
                                                        {{date_format(date_create($attendanc->InTime), "d-M-Y h:i A")}}
                                                    @else
                                                        <span class="badge badge-primary">{{"Holiday"}}</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if(count($holidays) == 0)
                                                @if(in_array("$att_date", $result))
                                                    @if($attendanc->OutTime != '1900-01-01 00:00:00')
                                                        <span class="badge badge-success">Sunday({{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}
                                                            )</span>
                                                    @else
                                                        <span class="badge badge-success">Sunday</span>
                                                    @endif

                                                @else
                                                    @if($attendanc->OutTime != '1900-01-01 00:00:00')
                                                        {{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}
                                                    @else
                                                        <span class="badge badge-danger">{{"Absent"}}</span>
                                                    @endif
                                                @endif
                                            @else
                                                @if(!in_array("$att_date", $result))
                                                    @if($attendanc->OutTime != '1900-01-01 00:00:00')
                                                        <span class="badge badge-primary">{{"Holiday"}}
                                                            ({{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}
                                                            )</span>
                                                    @else
                                                        <span class="badge badge-primary">{{"Holiday"}}</span>
                                                    @endif

                                                @else
                                                    @if($attendanc->OutTime != '1900-01-01 00:00:00')
                                                        {{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}
                                                    @else
                                                        <span class="badge badge-primary">{{"Holiday"}}</span>
                                                    @endif
                                                @endif
                                            @endif


                                            {{--@if(in_array("$att_date", $result))--}}
                                            {{--@if($attendanc->OutTime != '1900-01-01 00:00:00')--}}
                                            {{--<span class="badge badge-success">Sunday({{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}--}}
                                            {{--)</span>--}}
                                            {{--@else--}}
                                            {{--<span class="badge badge-success">Sunday</span>--}}
                                            {{--@endif--}}
                                            {{--@else--}}
                                            {{--@if($attendanc->OutTime != '1900-01-01 00:00:00')--}}
                                            {{--{{date_format(date_create($attendanc->OutTime), "d-M-Y h:i A")}}--}}
                                            {{--@else--}}
                                            {{--{{"Absent"}}--}}
                                            {{--@endif--}}
                                            {{--@endif--}}
                                        </td>
                                        <td>{!! isset($attendanc->C1)?$attendanc->C1 :'-' !!} </td>

                                        <td>
                                            @if($attendanc->C2 > 0)
                                                <button type="button" class="btn btn-xs btn-success"
                                                        onclick="view_attendance_log('{{$attDate}}','{{$empcode}}','{{$table}}')">
                                                    View Log
                                                </button>(Gatepass-:{{$attendanc->C2}})
                                                @php
                                                    $total_gatepass_min+=$attendanc->C2;
                                                @endphp
                                            @else
                                                {{"N/A"}}
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="5">< No Record Found ></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="row">
                            <h4>Total Gatepass Min: {{$total_gatepass_min}}</h4>
                        </div>


                    </div>
                </div>
            </div>


            <script>
                $(document).ready(function () {
                    $('#example2').DataTable({
                        "pageLength": 50,
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(window).scroll(function () {
                    var headerBottom = '.navbar.horizontal-layout .nav-bottom';
                    if ($(window).scrollTop() >= 70) {
                        $(headerBottom).addClass('fixed-top');
                    } else {
                        $(headerBottom).removeClass('fixed-top');
                    }
                });

                function view_attendance_log(att_date, emp_code, table) {
                    $('#my').modal('show');
                    $.get('{{ url('view_attendance_log') }}', {
                        att_date: att_date,
                        emp_code: emp_code,
                        table: table
                    }, function (data) {
                        $('#mh').html('View Attendance Logs');
                        $('#mb').html(data);

                    });
                }
            </script>


            <script>
                {{--function del_admin(id) {--}}
                {{--Swal({--}}
                {{--title: 'Are you sure?',--}}
                {{--text: "You won't be able to revert this!",--}}
                {{--type: 'warning',--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonColor: '#3085d6',--}}
                {{--cancelButtonColor: '#d33',--}}
                {{--confirmButtonText: 'Yes, delete it!'--}}
                {{--}).then((result) => {--}}
                {{--if (result.value)--}}
                {{--{--}}
                {{--$.get('{{ url('del_admin') }}', {--}}
                {{--did: id--}}
                {{--}, function (data) {--}}
                {{--$("#maindiv").load(location.href + " #maindiv");--}}
                {{--Swal(--}}
                {{--'Deleted!',--}}
                {{--'Your file has been deleted.',--}}
                {{--'success'--}}
                {{--)--}}
                {{--});--}}

                {{--}--}}
                {{--})--}}
                {{--}--}}

                function update_admin(id) {
                    $.get('{{ url('update_admin_form') }}', {
                        uid: id
                    }, function (data) {
                        $('#mh').html("Edit Admin Detail's");
                        $('#mb').html(data);
                        $('#myModal').modal('show');
                    });
                }
            </script>

@stop