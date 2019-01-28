@extends('master.master')
@section('title','7 EYE E-Commerce | Category List')
@section('content')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel container">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <button class="btn btn-success">
                                holiday List
                            </button>
                            <div class="card-header header-sm">

                                <div class="d-flex align-items-center">
                                    <a href="{{url('holiday')}}" class="btn btn-primary">Back</a>
                                    &nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{url('holiday')}}" method="post" id="category"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="Type">Projects</label>
                                            @php
                                                $projects = \App\EmployeeModel::where(['is_active' => '1'])->get();
                                            @endphp
                                            <select name="employee_id[]" id="hr_ids"
                                                    class="form-control required typeDD"
                                                    style="width: 100%;" multiple>
                                                <option value="0">All</option>
                                                @foreach ($projects as $item)
                                                    <option value="{{$item->EmployeeId}}">{{$item->EmployeeName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="username">Date</label>
                                            <input type="text" class="form-control dtp required" id="datepicker"
                                                   name="date"
                                                   autocomplete="off" placeholder="Date"
                                                   maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="username">Occasion</label>
                                            <input type="text" class="form-control required" id="datepicker"
                                                   name="occasion"
                                                   autocomplete="off" placeholder="Occasion"
                                                   maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn btn-info btn-sm" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.dtp').datepicker({
            format: "dd-M-yyyy",
            maxViewMode: 2,
            // endDate: '-18y',
            daysOfWeekHighlighted: "0",
            autoclose: true,
        });
    </script>
@stop