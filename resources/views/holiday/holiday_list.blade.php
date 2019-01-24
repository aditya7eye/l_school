@extends('master.master')
@section('title','7 EYE E-Commerce | Category List')
@section('content')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel container">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <button class="btn btn-success">
                                holiday List
                            </button>
                            <div class="card-header header-sm">

                                <div class="d-flex align-items-center">
                                    <button onclick="create_holiday()" class="btn btn-primary">Create Holiday</button>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example" class="table table-bordered w-100" >
                                    <thead style="background-color: #34BF9B;">
                                    <tr>
                                        <th class="border-bottom-0" style="color:white;">Holiday #</th>
                                        <th class="border-bottom-0" style="color:white;">Date</th>
                                        <th class="border-bottom-0" style="color:white;">Occasion</th>
                                        <th class="border-bottom-0" style="color:white;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach($holidays as $holiday)
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{ date_format(date_create($holiday->date), "d-M-Y")}}</td>

                                            <td>{{$holiday->occasion}}</td>
                                            <td>
                                                <a onclick="edit_holiday('{{$holiday->id}}')"
                                                   class="btn btn-outline-primary">Edit</a>
                                                <a onclick="delete_holiday('{{$holiday->id}}')"
                                                   class="btn btn-outline-danger">Delete</a>
                                            </td>
                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function edit_holiday(holiday_id) {
            $('#my').modal('show');
            $.get('{{ url('holiday_edit') }}', {holiday_id: holiday_id}, function (data) {
                $('#mh').html('Edit Holiday');
                $('#mb').html(data);

            });
        }

        function delete_holiday(holiday_id) {
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
                    $.get('{{ url('delete_holiday') }}', {holiday_id: holiday_id}, function (data) {
                        success_noti("Holiday has been deleted");
                        setTimeout(function () {
                            window.location.href = '{{url('holiday')}}'
                        }, 1000);
                    });

                }
            }
        )
            ;
        }


    </script>


@stop