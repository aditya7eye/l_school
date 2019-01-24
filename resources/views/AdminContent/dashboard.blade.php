@extends('master.master')
@section('title','School | Dashboard')
@section('content')
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card card-statistics">
                            <div class="row">
                                @php
                                    $employee_count = \App\EmployeeModel::where(['is_active'=>1])->count();
                                    $payroles = DB::select("SELECT DISTINCT(payrole.date), COUNT(id) as payrole_generated, created_time  FROM `payrole` WHERE 1 GROUP by payrole.date ORDER by payrole.date desc");
                                    $pf_esic_cal = \App\PFESIC::find(1);
                                    $attendancelogs = \Illuminate\Support\Facades\DB::table('attendancelogs')->orderBy('AttendanceLogId', 'DESC')->first();
                                @endphp
                                <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                            <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                                            <div class="wrapper text-center text-sm-left">
                                                <p class="card-text mb-0">Total Active Employees</p>
                                                <div class="fluid-container">
                                                    <h3 class="card-title mb-0">{{$employee_count}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                            <i class="mdi mdi-trophy text-primary mr-0 mr-sm-4 icon-lg"></i>
                                            <div class="wrapper text-center text-sm-left">
                                                <p class="card-text mb-0">Total Payroll</p>
                                                <div class="fluid-container">
                                                    <h3 class="card-title mb-0">{{count($payroles)}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                            <i class="mdi mdi-checkbox-marked-circle-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                                            <div class="wrapper text-center text-sm-left">
                                                <p class="card-text mb-0">PF Percent - <b>{{$pf_esic_cal->pf}}%</b></p>
                                                <p class="card-text mb-0">ESIC Percent - <b>{{$pf_esic_cal->esic}}%</b>
                                                </p>
                                                <p class="card-text mb-0">GatePass - <b>{{$pf_esic_cal->gate_pass_min}} Min</b>
                                                </p>
                                                {{--<div class="fluid-container">--}}
                                                {{--<h3 class="card-title mb-0">{{$pf_esic_cal->pf}}%</h3>--}}
                                                {{--</div>--}}
                                                {{--<p class="card-text mb-0">ESIC Percent</p>--}}
                                                {{--<div class="fluid-container">--}}
                                                {{--<h3 class="card-title mb-0">{{$pf_esic_cal->esic}}%</h3>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                            <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                                            <div class="wrapper text-center text-sm-left">
                                                <p class="card-text mb-0">Last Modified Date</p>
                                                <p class="card-text mb-0">
                                                    <b>{{ date_format(date_create($attendancelogs->AttendanceDate), "d-M-Y")}}</b>
                                                </p>

                                                {{--<div class="fluid-container">--}}
                                                {{--<h3 class="card-title mb-0"></h3>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $payroles = DB::select("SELECT DISTINCT(payrole.date), COUNT(id) as payrole_generated, created_time  FROM `payrole` WHERE 1 GROUP by payrole.date ORDER by payrole.date desc limit 5");
                                @endphp
                                <h4 class="card-title">Recent Payroll List <a href="{{url('create-payroll')}}" class="btn btn-xs btn-primary pull-right">View All</a></h4>
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
                                        {{--<th class="border-bottom-0" style="color:white;">Action</th>--}}
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
                                                <td><i class="mdi mdi-currency-inr"></i> {{ $total_pf }}</td>
                                                <td><i class="mdi mdi-currency-inr"></i> {{ $total_esic}}</td>
                                                <td><i class="mdi mdi-currency-inr"></i> {{ $total_payout }}</td>
                                                <td>{{date_format(date_create($payrole->created_time), "d-M-Y h:i A")}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td align="center" colspan="7">< No Record Found ></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Holiday List <a href="{{url('holiday')}}" class="btn btn-xs btn-primary pull-right">View All</a></h4>
                                <div class="fluid-container">
                                    @php
                                        $holidays = \App\Holiday::where(['is_active' => 1])->get();
                                    @endphp
                                    <table class=" table table-bordered" id="example1">
                                        <thead style="background-color: #34BF9B;">
                                        <tr>
                                            <th class="border-bottom-0" style="color:white;">Holiday #</th>
                                            <th class="border-bottom-0" style="color:white;">Holiday Date</th>
                                            <th class="border-bottom-0" style="color:white;">Holiday Occasion</th>
                                            {{--<th>Action</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $count = 1;
                                        @endphp
                                        @if(count($holidays)>0)
                                            @foreach($holidays as $holiday)
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <td>{{date_format(date_create($holiday->date), "d-M-Y")}}</td>
                                                    <td>{{$holiday->occasion}}</td>
                                                    {{--<td>--}}
                                                    {{--<a onclick="edit_holiday('{{$holiday->id}}')"--}}
                                                    {{--class="btn btn-outline-primary">Edit</a>--}}
                                                    {{--<a onclick="delete_holiday('{{$holiday->id}}')"--}}
                                                    {{--class="btn btn-outline-danger">Delete</a>--}}
                                                    {{--</td>--}}
                                                </tr>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="3"><span>< No Record Available ></span></td>
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

@stop