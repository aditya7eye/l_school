@extends('master.master')
@section('title','L.K.S.S.S. | Session List')
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
                        <h4 class="card-title">Session List</h4>
                        <hr>
                        <table class="center-aligned-table table table-bordered" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">#</th>
                                <th class="border-bottom-0" style="color:white;">Session</th>
                                <th class="border-bottom-0" style="color:white;">Start Date</th>
                                <th class="border-bottom-0" style="color:white;">End Date</th>
                                <th class="border-bottom-0" style="color:white;">Status</th>
                                <th class="border-bottom-0" style="color:white;">Option</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $ses = \App\SessionMaster::get();
                            @endphp

                            @if(count($ses) > 0)
                                @foreach ($ses as $index => $se)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $se->session }}</td>
                                        <td>{{ $se->start_date }}</td>
                                        <td>{{ $se->end_date }}</td>
                                        <td>
                                            @if($se->is_active == 1)
                                                <label class="badge badge-success">Active</label>
                                            @else
                                                <label class="badge badge-info">InActive</label>
                                            @endif
                                        </td>

                                        <td>
                                            <button type="button"
                                                    onclick="update_session('{{ $se->id }}');"
                                                    class="btn btn-primary ">Edit
                                            </button>
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
        function update_session(id) {
            $('#my').modal('show');
            $.get('{{ url('update_session_frm') }}', {sid: id}, function (data) {
                $('#mh').html('Edit Session');
                $('#mb').html(data);
            });
        }
    </script>

@stop