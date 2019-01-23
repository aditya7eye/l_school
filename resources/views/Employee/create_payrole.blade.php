@extends('master.master')
@section('title','L.K.S.S.S. | GPayroll Payrole')
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
                        <h4 class="card-title">Generate Payroll</h4>
                        <hr>
                        <form class="forms-sample" action="{{ url('generate_payroll') }}" method="get">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="exampleInputName1">Month</label>
                                        <select size="1" name="month" class="form-control">
                                            <option selected value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">

                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Year</label>
                                        @php
                                            $already_selected_value = 2019;
                                            $earliest_year = 2001;
                                            print '<select name="year" class="form-control">';
                                                foreach (range(date('Y'), $earliest_year) as $x) {
                                                print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                                                }
                                                print '</select>';
                                        @endphp
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputEmail3"></label><br>
                                    <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Generated Payroll List</h4>
                        <hr>
                        <table class=" table table-bordered" id="example">
                            <thead style="background-color: #34BF9B;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Month</th>
                                <th class="border-bottom-0" style="color:white;">Year</th>
                                <th class="border-bottom-0" style="color:white;">Total Payroll</th>
                                <th class="border-bottom-0" style="color:white;">Total PF</th>
                                <th class="border-bottom-0" style="color:white;">Total ESIC</th>
                                <th class="border-bottom-0" style="color:white;">Total Payout</th>
                                <th class="border-bottom-0" style="color:white;">Generated Date</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($payroles) > 0)
                                @foreach ($payroles as $index => $payrole)
                                    @php
                                        $array  = explode(",", $payrole->date);
                                    $total_pf = \App\Payrole::where(['date'=>$payrole->date])->sum('total_pf');
                                    $total_esic = \App\Payrole::where(['date'=>$payrole->date])->sum('total_esic');
                                    $total_payout = \App\Payrole::where(['date'=>$payrole->date])->sum('payout');
                                    @endphp
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $array[0], 10)) }}</td>
                                        <td>{{ $array[1]}}</td>
                                        <td>{{ $payrole->payrole_generated }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_pf }}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_esic}}</td>
                                        <td><i class="mdi mdi-currency-inr"></i>{{ $total_payout }}</td>
                                        <td>{{date_format(date_create($payrole->created_time), "d-M-Y h:i A")}}</td>
                                        <td>
                                            <a href="{{url('view-payroll').'/'.base64_encode($payrole->date)}}" class="btn btn-primary btn-sm">View</a>
                                            {{--<button type="button" onclick="del_payroll('{{$payrole->date}}')"  class="btn btn-sm btn-danger ">Delete</button>--}}

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

        function del_payroll(e_id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.get('{{ url('delete_payroll') }}', {date: e_id}, function (data) {
                        success_noti("Payroll has been deleted");
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    });

                }
            }
        )
            ;

        }



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

    <!-- partial -->




@stop