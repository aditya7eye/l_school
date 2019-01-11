@extends('master.master')
@section('title','School | Attendance List')
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
                        <h4>Attendance Report</h4>
                        <hr>
                        <table class="center-aligned-table table table-responsive table-bordered" style="height: 500px; overflow: scroll;">
                            <thead style="background-color: #3506065e;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Employee Code</th>
                                <th class="border-bottom-0" style="color:white;">Present Days</th>
                                <th class="border-bottom-0" style="color:white;">Absent Days</th>
                                <th class="border-bottom-0" style="color:white;">Normal Working Hours</th>
                                <th class="border-bottom-0" style="color:white;">OT Hours</th>
                                <th class="border-bottom-0" style="color:white;">OT Days</th>
                                <th class="border-bottom-0" style="color:white;">CL</th>
                                <th class="border-bottom-0" style="color:white;">PL</th>
                                <th class="border-bottom-0" style="color:white;">SL</th>
                                <th class="border-bottom-0" style="color:white;">Total Leave</th>
                                <th class="border-bottom-0" style="color:white;">Late Coming Days</th>
                                <th class="border-bottom-0" style="color:white;">Late Coming Hours</th>
                                <th class="border-bottom-0" style="color:white;">Early Going Days</th>
                                <th class="border-bottom-0" style="color:white;">Early Going Hours</th>
                                <th class="border-bottom-0" style="color:white;">Weekly Off</th>
                                <th class="border-bottom-0" style="color:white;">Weekly Off Present</th>
                                <th class="border-bottom-0" style="color:white;">Holiday</th>
                                <th class="border-bottom-0" style="color:white;">Holiday Present</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($employee_list) > 0)
                                @foreach ($employee_list as $index => $employee)
                                    <tr>
                                        @php
                                            $absent_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as absent_days FROM `attendancelogs` WHERE StatusCode = 'A' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                            $present_days = DB::selectOne("SELECT COUNT(AttendanceLogId) as present_days FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                            $OT_hours = DB::selectOne("SELECT sum(OverTime) as OT_hours FROM `attendancelogs` WHERE StatusCode = 'P' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");

                                        $WeeklyOff = DB::selectOne("SELECT count(AttendanceLogId) as WeeklyOff FROM `attendancelogs` WHERE StatusCode = 'WO' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                        $WeeklyOffPresent = DB::selectOne("SELECT count(AttendanceLogId) as WeeklyOffPresent FROM `attendancelogs` WHERE StatusCode = 'WO' and Present = '1' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                        $Holiday = DB::selectOne("SELECT count(AttendanceLogId) as Holiday FROM `attendancelogs` WHERE StatusCode = 'WO' and Holiday = '1' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                        $HolidayPresent = DB::selectOne("SELECT count(AttendanceLogId) as HolidayPresent FROM `attendancelogs` WHERE StatusCode = 'WO' and Holiday = '1' and Present = '1' and EmployeeId = $employee->EmployeeId and MONTH(AttendanceDate) = 08 AND YEAR(AttendanceDate) = 2018");
                                        @endphp
                                        <td>{{ $employee->EmployeeCode }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $absent_days->absent_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>@php

                                                $minutes = $OT_hours->OT_hours;
        $zero    = new DateTime('@0');
        $offset  = new DateTime('@' . $minutes * 60);
        $diff    = $zero->diff($offset);
       $diff->format('%a Days, %h Hours, %i Minutes');
                                        @endphp


                                            {{  $diff->format('%h Hrs, %i Mns') }}</td>
                                        <td>{{  $diff->format('%a Days') }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $present_days->present_days }}</td>
                                        <td>{{ $WeeklyOff->WeeklyOff }}</td>
                                        <td>{{ $WeeklyOffPresent->WeeklyOffPresent }}</td>
                                        <td>{{ $Holiday->Holiday }}</td>
                                        <td>{{ $HolidayPresent->HolidayPresent }}</td>
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
                        <div class="row">
                        </div>


                    </div>
                </div>
            </div>


            <script>$(window).scroll(function () {
                    var headerBottom = '.navbar.horizontal-layout .nav-bottom';
                    if ($(window).scrollTop() >= 70) {
                        $(headerBottom).addClass('fixed-top');
                    } else {
                        $(headerBottom).removeClass('fixed-top');
                    }
                });
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