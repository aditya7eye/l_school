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
                        <h4 class="card-title">Generate Payrole</h4>
                        <hr>
                        <form class="forms-sample" action="{{ url('generate_payrole') }}" method="get">
                            <div>
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

                                <button type="submit" class="btn btn-warning mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Generated Payroll List</h4>
                        <hr>
                        <table class=" table table-bordered">
                            <thead style="background-color: #3506065e;">
                            <tr>
                                <th class="border-bottom-0" style="color:white;">Month</th>
                                <th class="border-bottom-0" style="color:white;">Year</th>
                                <th class="border-bottom-0" style="color:white;">Total Payroll</th>
                                <th class="border-bottom-0" style="color:white;">Date</th>
                                <th class="border-bottom-0" style="color:white;">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($payroles) > 0)
                                @foreach ($payroles as $index => $payrole)
                                    @php
                                        $array  = explode(",", $payrole->date);
                                    @endphp
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $array[0], 10)) }}</td>
                                        <td>{{ $array[1]}}</td>
                                        <td>{{ $payrole->payrole_generated }}</td>
                                        <td>{{ $payrole->created_time }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{url('view-payroll').'/'.base64_encode($payrole->date )}}">View</a>
                                            <button onclick="del_payroll('{{ $payrole->date }}');"
                                                    class="btn btn-danger ">Delete
                                            </button>
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
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
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
        );
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