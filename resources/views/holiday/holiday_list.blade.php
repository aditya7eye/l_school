@extends('master.admin_master')
@section('title','7 EYE E-Commerce | Category List')
@section('content')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel container">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-header header-sm">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title">Size List</h5>&nbsp;&nbsp;
                                    <button href="#" onclick="create_size()" class="pull-right btn btn-success">
                                        Create Size
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="order-listing" class="table table-striped w-100">
                                    <thead>
                                    <tr>
                                        <th>Size #</th>
                                        <th>Size Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach($sizes as $category)
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$category->size}}</td>
                                            <td>
                                                <a onclick="edit_size('{{$category->id}}')"
                                                   class="btn btn-outline-primary">Edit</a>
                                                <a onclick="delete_size('{{$category->id}}')" class="btn btn-outline-danger">Delete</a>
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
        function edit_size(size_id) {
            $('#my').modal('show');
            $.get('{{ url('size_edit') }}', {size_id: size_id}, function (data) {
                $('#mh').html('Edit Size');
                $('#mb').html(data);

            });
        }

        function delete_size(size_id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                    if (willDelete) {
                        $.get('{{ url('delete_size') }}', {size_id: size_id}, function (data) {
                            success_noti("Size has been deleted");
                            setTimeout(function () {
                                window.location.href = '{{url('size')}}'
                            }, 1000);
                        });

                    }
                }
            );
        }

    </script>


@stop