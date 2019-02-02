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
                        <table class="center-aligned-table table table-bordered" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">#</th>
                                <th class="border-bottom-0" style="color:white;">EmployeeName</th>
                                <th class="border-bottom-0" style="color:white;">EmployeeCode</th>
                                <th class="border-bottom-0" style="color:white;">Date Of Joining</th>
                                <th class="border-bottom-0" style="color:white;">Salary</th>
                                <th class="border-bottom-0" style="color:white;">Designation</th>
                                <th class="border-bottom-0" style="color:white;">Is PF Applied</th>
                                <th class="border-bottom-0" style="color:white;">Status</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($employeelist) > 0)
                                @foreach ($employeelist as $index => $employeelistobj)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ ucwords($employeelistobj->EmployeeName) }}</td>
                                        <td>{{ $employeelistobj->EmployeeCode }}</td>
                                        <td>{{ date_format(date_create($employeelistobj->DOJ), "d-M-Y")}}</td>
                                        <td>{{ $employeelistobj->salary }}</td>
                                        <td>{{ isset($employeelistobj->emp_type->type)?$employeelistobj->emp_type->type :'-'}}</td>
                                        <td>{{ $employeelistobj->is_pf_applied == 1 ?'Applied':'N/A' }}</td>
                                        <td>           @if($employeelistobj->is_active == 1)
                                                <label class="badge badge-success">Active</label>
                                            @else
                                                <label class="badge badge-info">InActive</label>
                                            @endif
                                        </td>
                                        <td>
                                            {{--<a --}}{{--target="_blank"--}}
                                               {{--href="{{url('edit_employee?eid=').$employeelistobj->EmployeeId}}"--}}
                                               {{--onclick="OpenInNewTabWinBrowser('{{url('edit_employee?eid=').$employeelistobj->EmployeeId}}');"--}}
                                               {{--onclick="update_employee('{{ $employeelistobj->EmployeeId }}');"--}}{{-- class="btn btn-primary ">Edit--}}
                                            {{--</a>--}}
                                            <button type="button" onclick="OpenInNewTabWinBrowser('{{url('edit_employee?eid=').$employeelistobj->EmployeeId}}');" class="btn btn-primary">Edit</button>
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
    </script>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

    <script>
        function OpenInNewTabWinBrowser(url) {
//            var win = window.open(url, '_blank');
//            win.focus();
            window.open(url, null, "width=800,height=800,left=600,modal=yes,alwaysRaised=yes", null);
        }
        {{--function del_admin(e_id) {--}}
        {{--swal({--}}
        {{--title: 'Are you sure?',--}}
        {{--text: "You won't be able to revert this!",--}}
        {{--type: 'warning',--}}
        {{--showCancelButton: true,--}}
        {{--confirmButtonColor: '#3085d6',--}}
        {{--cancelButtonColor: '#d33',--}}
        {{--confirmButtonText: 'Yes, Inactivate Employee'--}}
        {{--}).then((willDelete) => {--}}
        {{--if (willDelete) {--}}
        {{--$.get('{{ url('inactive_employee') }}', {e_id: e_id}, function (data) {--}}
        {{--success_noti("Employee has been inactivated");--}}
        {{--setTimeout(function () {--}}
        {{--window.location.href = '{{url('employee-manage')}}'--}}
        {{--}, 2000);--}}
        {{--});--}}

        {{--}--}}
        {{--}--}}
        {{--)--}}
        {{--;--}}
        {{--}--}}
        {{--function active_admin(e_id) {--}}
        {{--swal({--}}
        {{--title: 'Are you sure?',--}}
        {{--text: "You won't be able to revert this!",--}}
        {{--type: 'warning',--}}
        {{--showCancelButton: true,--}}
        {{--confirmButtonColor: '#3085d6',--}}
        {{--cancelButtonColor: '#d33',--}}
        {{--confirmButtonText: 'Yes, Activate Employee'--}}
        {{--}).then((willDelete) => {--}}
        {{--if (willDelete) {--}}
        {{--$.get('{{ url('active_employee') }}', {e_id: e_id}, function (data) {--}}
        {{--success_noti("Employee has been activated");--}}
        {{--setTimeout(function () {--}}
        {{--window.location.href = '{{url('employee-manage')}}'--}}
        {{--}, 1000);--}}
        {{--});--}}

        {{--}--}}
        {{--}--}}
        {{--)--}}
        {{--;--}}
        {{--}--}}

        function update_employee(id) {
            $('#my').modal('show');
            $('#modal_type').removeClass('modal-md');
            $('#modal_type').addClass('modal-lg');
            $.get('{{ url('edit_employee') }}', {eid: id}, function (data) {
                $('#mh').html('Edit Employee');
                $('#mb').html(data);
            });
        }
    </script>

@stop