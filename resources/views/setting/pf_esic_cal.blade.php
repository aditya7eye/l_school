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
                        <h4 class="card-title">PF and ESIC Calculation</h4>
                        <hr>
                        <table class="center-aligned-table table table-bordered">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">PF</th>
                                <th class="border-bottom-0" style="color:white;">ESIC</th>
                                <th class="border-bottom-0" style="color:white;">Gate Pass Min</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>
                            @php
                                $employeelist = \App\PFESIC::first();
                            @endphp
                            <tbody>
                            <tr>
                                <td>{{ $employeelist->pf }}</td>
                                <td>{{ $employeelist->esic }}</td>
                                <td>{{ $employeelist->gate_pass_min }} Min</td>
                                <td>
                                    <button onclick="update_pf({{ $employeelist->id }})"
                                            class="btn btn-primary ">Edit
                                    </button>
                                </td>
                            </tr>
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

    <script>

        function update_pf(id) {
            $('#my').modal('show');
            $.get('{{ url('update_pf_form') }}', {uid: id}, function (data) {
                $('#mh').html('Edit Percents');
                $('#mb').html(data);
            });
        }
    </script>

@stop