@extends('master.master')
@section('title','L.K.S.S.S. | GPayroll Payrole')
@section('content')
    <style>
        .mybg {
            padding: 10px 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #6e80ce;
            border-radius: 8px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #0087b3;
        }

        .style-scroll::-webkit-scrollbar {
            width: 10px;
            height: 10px;
            border-width: thin;
            border-style: solid;
            border-color: #0087b3;
            border-image: initial;
        }

        .style-scroll::-webkit-scrollbar-button {
            width: 0px;
            height: 0px;
            display: none;
        }

        .style-scroll::-webkit-scrollbar-corner {
            background-color: transparent;
        }

        .style-scroll::-webkit-scrollbar-thumb {
            background-color: #0087b3;
            box-shadow: rgba(0, 0, 0, 0.1) 1px 1px 0px inset, rgba(0, 0, 0, 0.07) 0px -1px 0px inset;
        }

        #exampleView > thead > tr > th
        {
            font-weight:600 !important;
            font-size:12px !important;
            padding:10px !important;
            text-align: center;
        }
        #exampleView > tbody > tr > td
        {
            padding:2px !important;
            font-weight:600 !important;
            font-size: 12px !important;
            text-align: center;
        }

    </style>

    <div class="container-fluid page-body-wrapper" id="maindiv">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            @if(count($payroles)>0)
                                @php
                                    $array  = explode(",", $date);
                                @endphp
                                {{date('F', mktime(0, 0, 0, $array[0], 10)).", ".$array[1]  }} @endif Payroll List <a
                                    href="{{url('create-payroll')}}" class="pull-right btn btn-xs btn-success">Go
                                Back</a></h4>
                        <hr>
                        <table class="table table-responsive style-scroll table-bordered table-sm" id="exampleView">
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
                                <th class="border-bottom-0" style="color:white;">Pre. Gatepass Minute</th>
                                <th class="border-bottom-0" style="color:white;">Gatepass Minute</th>
                                <th class="border-bottom-0" style="color:white;">Total Gatepass</th>
                                <th class="border-bottom-0" style="color:white;">Leave Without Pay</th>
                                @if($temp == 1)
                                    <th class="border-bottom-0" style="color:white;">Modified Leave</th>
                                @endif
                                <th class="border-bottom-0" style="color:white;">Overtime Minute</th>
                                <th class="border-bottom-0" style="color:white;">Paid Leave</th>
                                <th class="border-bottom-0" style="color:white;">Salary</th>
                                <th class="border-bottom-0" style="color:white;">Gross Salary</th>
                                <th class="border-bottom-0" style="color:white;">Total PF</th>
                                <th class="border-bottom-0" style="color:white;">Total ESIC</th>
                                <th class="border-bottom-0" style="color:white;">Total Deduction</th>
                                <th class="border-bottom-0" style="color:white;">Payout</th>
                                @if($temp == 1)
                                    <th class="border-bottom-0" style="color:white;">Action</th>
                                @endif

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
                                        <td>{{ $payrole->previous_gatepassmin }}</td>
                                        <td>{{ $payrole->gatepassmin }}</td>
                                        <td>{{ $payrole->total_gatepass }}</td>
                                        <td>{{ $payrole->lwp }}</td>
                                        @if($temp == 1)
                                            <td>{{ isset($payrole->modified_lwp) ? $payrole->modified_lwp :'-'}}</td>
                                        @endif
                                        <td>{{ $payrole->overtime_min }}</td>
                                        <td>{{ $payrole->paid_leave }}</td>
                                        <td>{{ $payrole->salary }}</td>
                                        <td>{{ $payrole->gross_salary }}</td>
                                        <td>{{ $payrole->total_pf }}</td>
                                        <td>{{ $payrole->total_esic }}</td>
                                        <td>{{ $payrole->total_deduction }}</td>
                                        <td>{{ $payrole->payout }}</td>
                                        @if($temp == 1)
                                            <td>
                                                @if($payrole->absent_days > 0)
                                                    <div class="dropdown btn-sm">
                                                        <button type="button"
                                                                class="btn btn-success btn-xs dropdown-toggle"
                                                                data-toggle="dropdown">
                                                            Option
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#"
                                                               onclick="update_temp_payroll('{{ $payrole->id }}');">Edit
                                                                Paid Leave</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge badge-danger">No Leave Taken</span>
                                                @endif
                                            </td>
                                        @endif

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

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script>
        $('#exampleView').DataTable({
            "pageLength": 25,
            dom: 'Bfrtip',
            buttons: [
//                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
//                'pdfHtml5'
            ]
        });
        $(window).scroll(function () {
            var headerBottom = '.navbar.horizontal-layout .nav-bottom';
            if ($(window).scrollTop() >= 70) {
                $(headerBottom).addClass('fixed-top');
            } else {
                $(headerBottom).removeClass('fixed-top');
            }
        });
        $(document).ready(function () {
            $('#payroll_list').DataTable({
                "scrollX": true
            });
        });

        function update_temp_payroll(id) {
            $('#my').modal('show');
            $.get('{{ url('edit_temp_payroll') }}', {tid: id}, function (data) {
                $('#mh').html('Edit Temporary Payroll Leave');
                $('#mb').html(data);
            });
        }
    </script>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

    <!-- partial -->




@stop