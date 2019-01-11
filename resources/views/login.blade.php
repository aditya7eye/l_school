<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url('vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">

    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    {{--
    <link rel="stylesheet" href="{{ url('css/style2.css') }}"> --}}
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ url('images/favicon.png') }}" />



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

        .auth.theme-two .banner-section .slide-content.bg-1 {
            background: url("bg.jpg") no-repeat center center;
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper auth p-0 theme-two">
                <div class="row d-flex align-items-stretch">
                    <div class="col-md-4 banner-section d-none d-md-flex align-items-stretch justify-content-center">
                        <div class="slide-content bg-1">
                        </div>
                    </div>
                    <div class="col-12 col-md-8 h-100 bg-white">
                        <div class="auto-form-wrapper d-flex align-items-center justify-content-center flex-column">
                            <div class="nav-get-started">
                                @if(session()->has('message'))


                               
                                <div class="alert alert-danger alert-dismissible fade show">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Warning !</strong>  <a href="#" class="alert-link">{{ session()->get('message') }}</a>.
                                      </div>
                                @endif {{--
                                <p>Don't have an account?</p>
                                <a class="btn get-started-btn" href="register-2.html">GET STARTED</a> --}}
                            </div>
                            <form action="{{ url('/logincheck') }}" method="post">
                                {{ csrf_field() }}
                                <h3 class="mr-auto">Hello! let's get started</h3>
                                <p class="mb-5 mr-auto">Enter your details below.</p>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-lock-outline"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary submit-btn">SIGN IN</button>
                                </div>

                                <div class="wrapper mt-5 text-gray">
                                    <p class="footer-text">Copyright © 2018 7eyeitsolution. All rights reserved.</p>
                                    {{--
                                    <ul class="auth-footer text-gray">
                                        <li><a href="#">Terms & Conditions</a></li>
                                        <li><a href="#">Cookie Policy</a></li>
                                    </ul> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
</body>

</html>
<!-- container-scroller -->
<!-- plugins:js -->