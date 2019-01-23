<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url('vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="shortcut icon" href="{{ url('images/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ url('css/datepicker.css') }}">

    {{-------Notification--------}}
    <link rel="stylesheet" href="{{ url('css/lobibox.min.css') }}">
    <script src="{{url('js/notification.min.js')}}"></script>
    <script src="{{url('js/notification-custom-script.js')}}"></script>
    {{-------Notification--------}}

    <style>
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 90%;
            bottom: 30px;
            font-size: 17px;
        }

        #snackbar.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }
            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }
            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }
            to {
                bottom: 0;
                opacity: 0;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }
            to {
                bottom: 0;
                opacity: 0;
            }
        }

        .errorClass {
            border: 1px solid red !important;
        }

    </style>
</head>

<body>
<div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
        <div class="container d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-top">
                <a class="navbar-brand brand-logo" href="{{ url('dashboard') }}"><img
                            src="{{ url('images/s_logo.png') }}" alt="logo"/></a>
                {{--<a class="navbar-brand brand-logo-mini" href="{{ url('dashboard') }}"><img--}}
                {{--src="{{ url('images/logo-mini.svg') }}" alt="logo"/></a>--}}
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <form class="search-field ml-auto" action="#">
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                            </div>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-nav-right mr-0">

                    <li class="nav-item dropdown">

                        <img class="img-xs rounded-circle" src="{{ url('images/faces/face1.jpg') }}"
                             alt="Profile image">

                    </li>
                </ul>

                <span class="mdi mdi-menu"></span>
            </div>
        </div>
        <div class="nav-bottom">
            <div class="container">
                <ul class="nav page-navigation">
                    <li class="nav-item">
                        <a href="{{ url('dashboard') }}" class="nav-link"><i
                                    class="link-icon mdi mdi-television"></i><span
                                    class="menu-title">DASHBOARD</span></a>
                    </li>
                    {{--<li class="nav-item">--}}
                    {{--<a href="pages/widgets.html" class="nav-link"><i--}}
                    {{--class="link-icon mdi mdi-apple-safari"></i><span--}}
                    {{--class="menu-title">WIDGETS</span></a>--}}
                    {{--</li>--}}

                    {{--<li class="nav-item mega-menu">--}}
                    {{--<a href="#" class="nav-link"><i class="link-icon mdi mdi-flag-outline"></i><span--}}
                    {{--class="menu-title">Employees</span><i class="menu-arrow"></i></a>--}}
                    {{--<div class="submenu">--}}
                    {{--<div class="col-group-wrapper row">--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Miscellaneous</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{----}}

                    {{--<li class="nav-item"><a class="nav-link" href="pages/samples/register.html">Register</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/samples/register-2.html">Register--}}
                    {{--2</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/samples/lock-screen.html">Lockscreen</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/samples/landing.html">Landing--}}
                    {{--screen</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Payroll</p>--}}
                    {{--<ul class="submenu-item">--}}



                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/samples/error-500.html">500</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/samples/error-505.html">505</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Options</p>--}}
                    {{--<ul class="submenu-item">--}}



                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/samples/orders.html">Orders</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-12">--}}
                    {{--<p class="category-heading">Options</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{--<li class="nav-item"><a class="nav-link" href="#">Option 2</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-12 mt-3">--}}
                    {{--<p class="category-heading">Documentation</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="#">Documentation</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}

                    {{--<li class="nav-item mega-menu">--}}
                    {{--<a href="#" class="nav-link"><i class="link-icon mdi mdi-android-studio"></i><span--}}
                    {{--class="menu-title">FORMS</span><i class="menu-arrow"></i></a>--}}
                    {{--<div class="submenu">--}}
                    {{--<div class="col-group-wrapper row">--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Basic Elements</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic--}}
                    {{--Elements</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/forms/advanced_elements.html">Advanced--}}
                    {{--Elements</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/forms/validation.html">Validation</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/forms/wizard.html">Wizard</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/forms/text_editor.html">Text--}}
                    {{--Editor</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/forms/code_editor.html">Code--}}
                    {{--Editor</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Charts</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/chartjs.html">Chart--}}
                    {{--Js</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/charts/morris.html">Morris</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/flot-chart.html">Flaot</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/google-charts.html">Google--}}
                    {{--Chart</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/sparkline.html">Sparkline</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/c3.html">C3--}}
                    {{--Chart</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/chartist.html">Chartist</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/charts/justGage.html">JustGage</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-group col-md-3">--}}
                    {{--<p class="category-heading">Maps</p>--}}
                    {{--<ul class="submenu-item">--}}
                    {{--<li class="nav-item"><a class="nav-link"--}}
                    {{--href="pages/maps/mapeal.html">Mapeal</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/maps/vector-map.html">Vector--}}
                    {{--Map</a></li>--}}
                    {{--<li class="nav-item"><a class="nav-link" href="pages/maps/google-maps.html">Google--}}
                    {{--Map</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Employees</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link" href="{{url('employee-manage')}}">Manage Employee</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{url('employee-leave-left')}}">Employee
                                        Leave Left</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{url('attendance_list')}}">Attendance
                                        List</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Payroll</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link" href="{{url('create-payroll')}}">Generate
                                        Payroll</a></li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Leave</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link"
                                                        href="{{url('employee-leave-type')}}">Leave Type Count</a></li>
                                <li class="nav-item"><a class="nav-link"
                                                        href="{{url('holiday')}}">Holiday</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Settings</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link" href="{{url('session_list')}}">Session</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{url('update_pf_list')}}">Update
                                        Percent</a>
                                </li>

                                <li class="nav-item"><a class="nav-link" href="{{ url('logout') }}">Logout</a></li>
                                {{--<li class="nav-item"><a class="nav-link" href="{{ url('manage-plan') }}">Manage Plan</a>--}}
                                {{--</li>--}}
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="my">
        <div class="modal-dialog modal-md" id="modal_type">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 id="mh" class="modal-title">Modal Heading</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="mb">
                    Modal body..
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div id="snackbar" class="TXT">
        <h5 id="mtm">Some text some message</h5>
    </div>
    <script>
        function mt(msg) {
            $('#mtm').html(msg);
            $('#snackbar').addClass('show');
            setTimeout(function () {
                $('#snackbar').removeClass('show');
            }, 5000);
        }
    </script>
    <!-- partial -->
    @if(session()->has('message'))
        <script type="text/javascript">
            success_noti("{{ session()->get('message') }}"); // mt('{{ session()->get('message') }}');
        </script>
    @endif
    @if(session()->has('errmessage'))
        <script type="text/javascript">
            warning_noti("{{ session()->get('errmessage') }}");
        </script>
@endif
@yield('content')
<!-- page-body-wrapper ends -->
    <footer class="footer">
        <div class="container-fluid clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a
                        href="http://7eyeitsolutions.com/"
                        target="_blank">7eyeitsolutions</a>. All rights reserved.</span>
        </div>
    </footer>
</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="mh">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="mb">
                Modal body..
            </div>

            <!-- Modal footer -->
            <div class="modal-footer" id="mf">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

{{-- <button type="button" onclick="mt('Admin Created Successfully');" class="btn btn-light">open</button> --}}

<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{ url('js/datepicker.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $('.dtp').datepicker({
        format: "dd-M-yyyy",
        maxViewMode: 2,
        // endDate: '-18y',
        endDate: '+0d',
        daysOfWeekHighlighted: "0",
        autoclose: true,
    });

    $(window).scroll(function () {
        var headerBottom = '.navbar.horizontal-layout .nav-bottom';
        if ($(window).scrollTop() >= 70) {
            $(headerBottom).addClass('fixed-top');
        } else {
            $(headerBottom).removeClass('fixed-top');
        }
    });

    function create_holiday() {
        var uid = 1;
        $('#my').modal('show');
        $.get('{{ url('holiday/create') }}', {uid: uid}, function (data) {
            $('#mh').html('Create New Holiday');
            $('#mb').html(data);
        });
    }
    $(document).ready(function () {
        $('#example').DataTable();
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>


</body>

</html>