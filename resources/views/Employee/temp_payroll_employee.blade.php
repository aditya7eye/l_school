{{--<input type="checkbox" name="employee_id[]" value="{{$emplyee->EmployeeId}}">{{$emplyee->EmployeeName}}&nbsp;&nbsp;&nbsp;--}}
{{--@endforeach--}}

<h4>Left Payroll Generation Employee List Of <span class="badge-success">{{$date}}</span> : </h4>
<table class="table table-bordered" id="example">
    <thead class="thead-light">
    <tr>
        {{--<th></th>--}}
        <th>
            <div class="pretty p-svg p-curve">
                <input type="checkbox" id="chkParent" onclick="ListCheckAll(this)"/>
                <div class="state p-success">
                    <!-- svg path -->
                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                              style="stroke: white;fill:white;"></path>
                    </svg>
                    <label></label>
                </div>

            </div>
        </th>
        <th>#</th>
        <th>Employee Name</th>
        <th>Employee Code</th>
        <th>Salary</th>
        <th>Is PF Applied</th>
    </tr>
    </thead>
    <tbody>

    @php $count = 1 @endphp
    @if(count($employees)>0)
        @foreach($employees as $emplyee)
            <tr>
                <td>
                    <div class="pretty p-svg p-curve">
                        <input type="checkbox" name="employee_id[]"
                               {{--{{$user_master->is_show_notification == 1 ?'checked':''}} --}}value="{{$emplyee->EmployeeId}}"
                               class="list_table"/>
                        <div class="state p-success">
                            <!-- svg path -->
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                      style="stroke: white;fill:white;"></path>
                            </svg>
                            <label></label>
                        </div>
                    </div>

                </td>
                <td>{{ $count }}</td>
                <td>{{$emplyee->EmployeeName}}</td>
                <td>{{$emplyee->EmployeeCode}}</td>
                <td>{{isset($emplyee->salary)?number_format("$emplyee->salary",2,".",","):'0'}}</td>
                <td>{{$emplyee->is_pf_applied == 1 ? 'Yes' : 'No'}}</td>
            </tr>
            @php $count++ @endphp
        @endforeach
    @else
        <tr>
            <td colspan="15" align="center">No Record Available</td>
        </tr>
    @endif
    </tbody>
</table>
<br>
<button type="submit" onclick="blockPage();" class="btn btn-success mr-2">Submit
</button>
<script>
    $(document).ready(function () {
        $('#chkParent').click(function () {
            var isChecked = $(this).prop("checked");
            $('#example tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
        });
    });

    function ListCheckAll(dis) {
        $('.list_table').find('input[type="checkbox"]').prop("checked", $(dis).prop("checked"));
    }

</script>