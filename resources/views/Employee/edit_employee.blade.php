@extends('master.master')
@section('title','L.K.S.S.S. | Edit Employee')
@section('content')
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
                        <h4 class="card-title">Edit Employee <a class="pull-right" href="{{url('employee-manage')}}">Show Employee List</a></h4>
                        <hr>
                        <form class="forms-sample" action="{{ url('update_employee') }}" id="employee" method="get">
                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label for="exampleInputName1">Employee Name</label>
                                        <input type="text" maxlength="25" class="form-control" id="emp_type"
                                               value="{{ $emp->EmployeeName }}"
                                               name="EmployeeName" placeholder="Employee Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputName1">Employee Code</label>
                                        <input type="text" maxlength="25" class="form-control" id="emp_type"
                                               value="{{ $emp->EmployeeCode }}"
                                               name="EmployeeCode" placeholder="Employee Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputName1">Date of Joining</label>
                                        <input type="text" maxlength="25" class="form-control dtp required"
                                               id="emp_type"
                                               value="{{ date_format(date_create($emp->DOJ), "d-M-Y")}}"
                                               name="doj" placeholder="Employee Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Employee Activation</label><br>
                                        <input type="radio" {{ $emp->is_active == '1'?'checked':'' }} name="is_active"
                                               value="1" checked>
                                        Active
                                        &nbsp;&nbsp;
                                        <input type="radio" {{ $emp->is_active == '0'?'checked':'' }} name="is_active"
                                               value="0">
                                        Inactive<br>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Employment Type</label><br>
                                        <input type="radio"
                                               {{ $emp->EmployementType == 'Temporary'?'checked':'' }} name="EmployementType"
                                               value="Temporary"
                                               checked>
                                        Temporary &nbsp;&nbsp;
                                        <input type="radio"
                                               {{ $emp->EmployementType == 'Permanent'?'checked':'' }} name="EmployementType"
                                               value="Permanent">
                                        Permanent<br>
                                    </div>
                                </div>
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Salary</label>
                                        <input type="text" maxlength="7" class="form-control required amount"
                                               name="salary"
                                               value="{{ $emp->salary }}"
                                               id="salary"
                                               placeholder="Salary">
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $employee_type = \App\EmployeeType::get();
                                        @endphp
                                        <label for="exampleInputEmail3">Employee Type</label>
                                        <select name="employee_type_id" class="form-control" id="">
                                            @foreach($employee_type as $type)
                                                <option {{$emp->employee_type_id == $type->id ? 'selected':''}} value="{{$type->id}}">{{$type->type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">PF Applied</label><br>
                                        <input type="radio"
                                               {{ $emp->is_pf_applied == '1'?'checked':'' }} name="is_pf_applied"
                                               value="1"
                                               checked>
                                        Applicable &nbsp;&nbsp;
                                        <input type="radio"
                                               {{ $emp->is_pf_applied == '0'?'checked':'' }} name="is_pf_applied"
                                               value="0">
                                        Not
                                        Applicable<br>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Timing</label><br>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="time" class="form-control" name="check_in"
                                                       value="{{$emp->check_in}}" placeholder="Check In Time">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="time" class="form-control" name="check_out"
                                                       value="{{$emp->check_out}}" placeholder="Check Out Time">
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" value="{{ $emp->EmployeeId }}" name="eid">
                                    <button type="submit" class="btn btn-success mr-2">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.dtp').datepicker({
            format: "dd-M-yyyy",
            maxViewMode: 2,
            // endDate: '-18y',
            daysOfWeekHighlighted: "0",
            autoclose: true
        });
    </script>
@stop