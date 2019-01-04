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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js"></script>
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
    </style>
</head>

<body>
<div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
        <div class="container d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-top">
                <a class="navbar-brand brand-logo" href="{{ url('dashboard') }}"><img
                            src="{{ url('images/logo-inverse.svg') }}" alt="logo"/></a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('dashboard') }}"><img
                            src="{{ url('images/logo-mini.svg') }}" alt="logo"/></a>
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
                    <li class="nav-item">
                        <a href="pages/widgets.html" class="nav-link"><i
                                    class="link-icon mdi mdi-apple-safari"></i><span
                                    class="menu-title">WIDGETS</span></a>
                    </li>

                    <li class="nav-item mega-menu">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-flag-outline"></i><span
                                    class="menu-title">PAGES</span><i class="menu-arrow"></i></a>
                        <div class="submenu">
                            <div class="col-group-wrapper row">
                                <div class="col-group col-md-3">
                                    <p class="category-heading">User Pages</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/login.html">Login</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/login-2.html">Login
                                                2</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/register.html">Register</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/register-2.html">Register
                                                2</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/lock-screen.html">Lockscreen</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/landing.html">Landing
                                                screen</a></li>
                                    </ul>
                                </div>
                                <div class="col-group col-md-3">
                                    <p class="category-heading">Error Pages</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/error-400.html">400</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/error-404.html">404</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/error-500.html">500</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/error-505.html">505</a></li>
                                    </ul>
                                </div>
                                <div class="col-group col-md-3">
                                    <p class="category-heading">E-commerce</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link" href="pages/samples/invoice.html">Invoice</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/pricing-table.html">Pricing
                                                Table</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/samples/orders.html">Orders</a></li>
                                    </ul>
                                </div>
                                <div class="col-group col-md-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="category-heading">Layout</p>
                                            <ul class="submenu-item">
                                                <li class="nav-item"><a class="nav-link" href="pages/layouts/rtl.html">RTL
                                                        Layout</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <p class="category-heading">Documentation</p>
                                            <ul class="submenu-item">
                                                <li class="nav-item"><a class="nav-link"
                                                                        href="pages/documentation.html">Documentation</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mega-menu">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-android-studio"></i><span
                                    class="menu-title">FORMS</span><i class="menu-arrow"></i></a>
                        <div class="submenu">
                            <div class="col-group-wrapper row">
                                <div class="col-group col-md-3">
                                    <p class="category-heading">Basic Elements</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic
                                                Elements</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/forms/advanced_elements.html">Advanced
                                                Elements</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/forms/validation.html">Validation</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/forms/wizard.html">Wizard</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/forms/text_editor.html">Text
                                                Editor</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/forms/code_editor.html">Code
                                                Editor</a></li>
                                    </ul>
                                </div>
                                <div class="col-group col-md-3">
                                    <p class="category-heading">Charts</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/chartjs.html">Chart
                                                Js</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/charts/morris.html">Morris</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/flot-chart.html">Flaot</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/google-charts.html">Google
                                                Chart</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/sparkline.html">Sparkline</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/c3.html">C3
                                                Chart</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/chartist.html">Chartist</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="pages/charts/justGage.html">JustGage</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-group col-md-3">
                                    <p class="category-heading">Maps</p>
                                    <ul class="submenu-item">
                                        <li class="nav-item"><a class="nav-link"
                                                                href="pages/maps/mapeal.html">Mapeal</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/maps/vector-map.html">Vector
                                                Map</a></li>
                                        <li class="nav-item"><a class="nav-link" href="pages/maps/google-maps.html">Google
                                                Map</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">APPS</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link" href="pages/apps/email.html">Email</a></li>
                                <li class="nav-item"><a class="nav-link" href="pages/apps/calendar.html">Calendar</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="pages/apps/todo.html">Todo List</a></li>
                                <li class="nav-item"><a class="nav-link" href="pages/apps/gallery.html">Gallery</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Settings</span><i
                                    class="menu-arrow"></i></a>
                        <div class="submenu">
                            <ul class="submenu-item">
                                <li class="nav-item"><a class="nav-link" href="{{ url('manage-admin') }}">Manage
                                        Admin</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ url('manage-plan') }}">Manage Plan</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="snackbar" class="TXT">
        <h5 id="mtm">Some text some message</h5>
    </div>
    <script>
        function mt(msg) {
            $('#mtm').html(msg);
            $('#snackbar').addClass('show');
            setTimeout(function () {
                $('#snackbar').removeClass('show');
            }, 3000);
        }
    </script>
    <!-- partial -->
    @if(session()->has('message'))
        <script type="text/javascript">
            mt('{{ session()->get('message') }}');
        </script>
@endif
@yield('content')
<!-- page-body-wrapper ends -->
    <footer class="footer">
        <div class="container-fluid clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a
                        href="http://7eyeitsolutions.com/"
                        target="_blank">7eyeitsolutions</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i
                        class="mdi mdi-heart text-danger"></i></span>
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


</body>

</html>