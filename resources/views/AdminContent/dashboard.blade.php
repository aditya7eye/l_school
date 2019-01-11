
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
                            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                        <i class="mdi mdi-trophy-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                                        <div class="wrapper text-center text-sm-left">
                                            <p class="card-text mb-0">Total Employees</p>
                                            <div class="fluid-container">
                                                <h3 class="card-title mb-0">17,583</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                        <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                                        <div class="wrapper text-center text-sm-left">
                                            <p class="card-text mb-0">Total Payroll</p>
                                            <div class="fluid-container">
                                                <h3 class="card-title mb-0">65,650</h3>
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
                                            <p class="card-text mb-0">Total Holiday</p>
                                            <div class="fluid-container">
                                                <h3 class="card-title mb-0">32,604</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                        <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                                        <div class="wrapper text-center text-sm-left">
                                            <p class="card-text mb-0">Total Leaves</p>
                                            <div class="fluid-container">
                                                <h3 class="card-title mb-0">61,119</h3>
                                            </div>
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
                            <h5 class="card-title mb-4">Recent Payroll</h5>
                            <div class="fluid-container">
                                <table class="table center-aligned-table">
                                    <thead>
                                    <tr class="bg-light">
                                        <th class="border-bottom-0">ID</th>
                                        <th class="border-bottom-0">Assignee</th>
                                        <th class="border-bottom-0">Task Details</th>
                                        <th class="border-bottom-0">Payment Method</th>
                                        <th class="border-bottom-0">Payment Status</th>
                                        <th class="border-bottom-0">Amount</th>
                                        <th class="border-bottom-0">Tracking Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>#320</td>
                                        <td>Mark C.Diaz</td>
                                        <td>Support of thteme</td>
                                        <td>Credit card</td>
                                        <td><label class="badge badge-success">Approved</label></td>
                                        <td>$12,245</td>
                                        <td>JPBBN435893458</td>
                                    </tr>
                                    <tr>
                                        <td>#321</td>
                                        <td>Jose D</td>
                                        <td>Verify your email address !</td>
                                        <td>Internet banking</td>
                                        <td><label class="badge badge-warning">Pending</label></td>
                                        <td>$12,245</td>
                                        <td>BDYBN435893325</td>
                                    </tr>
                                    <tr>
                                        <td>#322</td>
                                        <td>Philips T</td>
                                        <td>Item support message send</td>
                                        <td>Credit card</td>
                                        <td><label class="badge badge-success">Approved</label></td>
                                        <td>$12,245</td>
                                        <td>JSNTN435884258</td>
                                    </tr>
                                    <tr>
                                        <td>#323</td>
                                        <td>Luke Pixel</td>
                                        <td>New submission on website</td>
                                        <td>Cash on delivery</td>
                                        <td><label class="badge badge-danger">Rejected</label></td>
                                        <td>$12,245</td>
                                        <td>JPABT435893678</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Holiday List</h5>
                            <div class="fluid-container">
                                <table class="table center-aligned-table">
                                    <thead>
                                    <tr class="bg-light">
                                        <th class="border-bottom-0">ID</th>
                                        <th class="border-bottom-0">Assignee</th>
                                        <th class="border-bottom-0">Task Details</th>
                                        <th class="border-bottom-0">Payment Method</th>
                                        <th class="border-bottom-0">Payment Status</th>
                                        <th class="border-bottom-0">Amount</th>
                                        <th class="border-bottom-0">Tracking Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>#320</td>
                                        <td>Mark C.Diaz</td>
                                        <td>Support of thteme</td>
                                        <td>Credit card</td>
                                        <td><label class="badge badge-success">Approved</label></td>
                                        <td>$12,245</td>
                                        <td>JPBBN435893458</td>
                                    </tr>
                                    <tr>
                                        <td>#321</td>
                                        <td>Jose D</td>
                                        <td>Verify your email address !</td>
                                        <td>Internet banking</td>
                                        <td><label class="badge badge-warning">Pending</label></td>
                                        <td>$12,245</td>
                                        <td>BDYBN435893325</td>
                                    </tr>
                                    <tr>
                                        <td>#322</td>
                                        <td>Philips T</td>
                                        <td>Item support message send</td>
                                        <td>Credit card</td>
                                        <td><label class="badge badge-success">Approved</label></td>
                                        <td>$12,245</td>
                                        <td>JSNTN435884258</td>
                                    </tr>
                                    <tr>
                                        <td>#323</td>
                                        <td>Luke Pixel</td>
                                        <td>New submission on website</td>
                                        <td>Cash on delivery</td>
                                        <td><label class="badge badge-danger">Rejected</label></td>
                                        <td>$12,245</td>
                                        <td>JPABT435893678</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>$(window).scroll(function() {
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