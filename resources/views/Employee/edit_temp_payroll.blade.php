<script src="{{ url('js/validate.js') }}"></script>
@php

    $employee_leave_left = \App\EmployeeLeaveLeft::where(['employee_id' => $payroll->employee_id, 'session_id' => $payroll->session_id])->first();
@endphp
<form class="forms-sample" action="{{ url('update_temp_payroll') }}" method="get">
    <div class="form-group">
        <label for="exampleInputName1">Employee Name</label>
        <input type="text" maxlength="4" class="form-control" id="emp_type"
               value="{{ $payroll->employee->EmployeeName }}" name="pf" readonly placeholder="Name">
    </div>
    <div class="form-group">
        <label for="exampleInputName1">LWP</label>
        <input type="text" maxlength="4" class="form-control" id="emp_type" value="{{ $payroll->lwp }}" name="lwp"
               readonly placeholder="Name">
    </div>
    <div class="form-group">
        <label for="exampleInputName1">Absent Days(Leave)</label>
        <input type="text" maxlength="4" class="form-control" id="emp_type" value="{{ $payroll->absent_days }}"
               name="absent" readonly placeholder="Name">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">Modified CL</label>
        <input type="text" maxlength="4" class="form-control required numberOnly" name="cl" placeholder="Modified CL"
               value="{{$payroll->cl}}" id="cl11"/>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">Modified ML</label>
        <input type="text" maxlength="4" class="form-control required numberOnly" name="ml" value="{{$payroll->ml}}"
               id="mlll" placeholder="Modified ML">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">GatePass Min</label>
        <input type="text" maxlength="2" class="form-control required" readonly name="gp"
               value="{{$payroll->gatepassmin}}">
    </div>
    <input type="hidden" value="{{ $payroll->id }}" name="tid">
    <button type="submit" class="btn btn-success mr-2">Update</button>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('#cl11').focusout(function () {
            var can_tak_max = parseFloat($(this).val()) + parseFloat($('#mlll').val());
            var max_cl = '{{$employee_leave_left->cl}}';
            var absent = '{{ $payroll->absent_days }}';
            if (parseFloat($(this).val()) > max_cl) {
                warning_noti("You don't have CL available...CL Available:" + max_cl);
                this.value = "";
            } else if (can_tak_max > absent) {
                warning_noti("Can not enter more than absent and ml");
                this.value = "";
            }

        });

    });
    $(document).ready(function () {
        $('#mlll').focusout(function () {
            var can_tak_max = parseFloat($(this).val()) + parseFloat($('#cl11').val());
            var max_ml = '{{$employee_leave_left->ml}}';
            var absent = '{{ $payroll->absent_days }}';
            if (parseFloat($(this).val()) > max_ml) {
                warning_noti("You don't have ML available...CL Available");
                this.value = "";
            } else if (can_tak_max > absent) {
                warning_noti("Can not enter more than absent and cl");
                this.value = "";
            }

        });

    });

</script>