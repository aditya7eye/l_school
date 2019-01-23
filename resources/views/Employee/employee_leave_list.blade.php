@extends('master.master')
@section('title','L.K.S.S.S. | Employee Leaves')
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
                        <h4 class="card-title">Employee Leave Left</h4>
                        <hr>
                        <table class="center-aligned-table table table-bordered" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">#</th>
                                <th class="border-bottom-0" style="color:white;">EmployeeName</th>
                                <th class="border-bottom-0" style="color:white;">Session</th>
                                <th class="border-bottom-0" style="color:white;">CL Left</th>
                                <th class="border-bottom-0" style="color:white;">ML Left</th>
                                <th class="border-bottom-0" style="color:white;">Total GatePass Min</th>
                                <th class="border-bottom-0" style="color:white;">Option</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($emp_leave_lefts) > 0)
                                @foreach ($emp_leave_lefts as $index => $emp_leave_left)
                                    @php
                                        $payroll = \App\Payrole::where(['session_id'=>$ses->id,'employee_id'=>$emp_leave_left->employee_id])->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ ucwords($emp_leave_left->employee->EmployeeName) }}</td>
                                        <td>{{ $ses->session }}</td>

                                        <td>{{isset($emp_leave_left->cl)? $emp_leave_left->cl : '-' }}</td>
                                        <td>{{isset($emp_leave_left->ml)? $emp_leave_left->ml : '-' }}</td>
                                        <td>{{ $emp_leave_left->gate_pass_min  }}</td>
                                        <td>
                                            @if(!isset($payroll))
                                                <button type="button"
                                                        onclick="update_leave_left('{{ $emp_leave_left->id }}');"
                                                        class="btn btn-primary ">Edit
                                                </button>
                                            @else
                                                <a disabled="disabled" data-toggle="tooltip"
                                                   title="Payroll already generated"> N/A</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="6">No Record Found</td>
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
        function update_leave_left(id) {
            $('#my').modal('show');
            $.get('{{ url('edit_employee_leave_left') }}', {eid: id}, function (data) {
                $('#mh').html('Edit Employee Leave Left');
                $('#mb').html(data);
            });
        }
    </script>

@stop