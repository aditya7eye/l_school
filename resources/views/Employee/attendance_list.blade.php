@extends('master.master')
@section('title','News | Attendance List')
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
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="center-aligned-table table table-bordered">
                                    <thead style="background-color: #3506065e;">
                                    <tr>
                                        <th class="border-bottom-0" style="color:white;">#</th>
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
                                        <th class="border-bottom-0" style="color:white;">Action</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $adminlist = \App\Admin_Model::where(['is_del' => 0])->orderBy('id','desc')->get();
                                    @endphp
                                    <tbody>
                                    @if(count($adminlist) > 0)
                                        @foreach ($adminlist as $index => $adminlistobj)
                                            <tr>
                                                <td># {{ $index+1 }}</td>
                                                <td>{{ ucwords($adminlistobj->name) }}</td>
                                                <td>{{ $adminlistobj->username }}</td>
                                                <td>{{ $adminlistobj->password }}</td>
                                                <td>
                                                    <button onclick="update_admin({{ $adminlistobj->id }})"
                                                            class="btn btn-primary ">Edit
                                                    </button>
                                                    <button onclick="del_admin({{ $adminlistobj->id }});"
                                                            class="btn btn-danger ">Delete
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