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
                        <h4 class="card-title">Leave Type Count List</h4>
                        <hr>
                        <table class="center-aligned-table table table-bordered">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">#</th>
                                <th class="border-bottom-0" style="color:white;">Type</th>
                                <th class="border-bottom-0" style="color:white;">CL</th>
                                <th class="border-bottom-0" style="color:white;">ML</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>
                            @php
                                $employeelist = \App\EmployeeType::where(['is_active'=>1])->get();
                            @endphp
                            <tbody>
                            @if(count($employeelist) > 0)
                                @foreach ($employeelist as $index => $employeelistobj)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ ucwords($employeelistobj->type) }}</td>
                                        <td>{{ $employeelistobj->cl }}</td>
                                        <td>{{ $employeelistobj->ml }}</td>
                                        <td>
                                            <button onclick="update_leave('{{ $employeelistobj->id }}');"
                                                    class="btn btn-primary ">Edit
                                            </button>
                                        </td>
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
        function update_leave(id) {
            $('#my').modal('show');
            $.get('{{ url('edit_employee-leave-type') }}', {lid: id}, function (data) {
                $('#mh').html('Edit Leave Count');
                $('#mb').html(data);
            });
        }
    </script>
@stop