@extends('master.master')
@section('title','L.K.S.S.S. | Manage Employee')
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
                        <h4 class="card-title">Employee List</h4>
                        <hr>
                        <table class="center-aligned-table table table-bordered">
                            <thead style="background-color: #3506065e;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">#</th>
                                <th class="border-bottom-0" style="color:white;">EmployeeName</th>
                                <th class="border-bottom-0" style="color:white;">Date Of Joining</th>
                                <th class="border-bottom-0" style="color:white;">Salary</th>
                                <th class="border-bottom-0" style="color:white;">Designation</th>
                                <th class="border-bottom-0" style="color:white;">Is PF Applied</th>
                                <th class="border-bottom-0" style="color:white;">Status</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>
                            @php
                                $employeelist = \App\EmployeeModel::where(['RecordStatus'=>1])->get();
                            @endphp
                            <tbody>
                            @if(count($employeelist) > 0)
                                @foreach ($employeelist as $index => $employeelistobj)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ ucwords($employeelistobj->EmployeeName) }}</td>
                                        <td>{{ date_format(date_create($employeelistobj->DOJ), "d-M-Y")}}</td>
                                        <td>{{ $employeelistobj->salary }}</td>
                                        <td>{{ $employeelistobj->emp_type->type }}</td>
                                        <td>{{ $employeelistobj->is_pf_applied == 1 ?'Applied':'-' }}</td>
                                        <td>{{ $employeelistobj->is_active == 1 ?'Active':'Inactive' }}</td>
                                        <td>
                                            @if($employeelistobj->RecordStatus == 1)
                                                <button onclick="del_admin('{{$employeelistobj->EmployeeId}}')" class="btn btn-primary">InActive</button>
                                            @else
                                                <button onclick="active_admin('{{$employeelistobj->EmployeeId}}')" class="btn btn-primary">Active</button>
                                            @endif
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


    <script>$(window).scroll(function () {
            var headerBottom = '.navbar.horizontal-layout .nav-bottom';
            if ($(window).scrollTop() >= 70) {
                $(headerBottom).addClass('fixed-top');
            } else {
                $(headerBottom).removeClass('fixed-top');
            }
        });</script>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

    <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>

    <script>
        function del_admin(e_id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete) {
                    $.get('{{ url('inactive_employee') }}', {e_id: e_id}, function (data) {
                        success_noti("Employee has been inactivated");
                        setTimeout(function () {
                            window.location.href = '{{url('holiday')}}'
                        }, 1000);
                    });

                }
            }
        );
        }
        function active_admin(e_id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete) {
                    $.get('{{ url('active_employee') }}', {e_id: e_id}, function (data) {
                        success_noti("Employee has been activated");
                        setTimeout(function () {
                            window.location.href = '{{url('holiday')}}'
                        }, 1000);
                    });

                }
            }
        );
        }

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