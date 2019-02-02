<script src="{{ url('js/validate.js') }}"></script>

<form class="forms-sample" id="leave" action="{{ url('update_employee_leave_left') }}" method="get">
    <div class="form-group">
        <label for="exampleInputName1">Employee Name</label>
        <input type="text" maxlength="4" class="form-control" disabled id="emp_type" value="{{ $employee_leave_left->employee->EmployeeName }}" name="pf" onkeypress="return false;" onkeydown="return false;" placeholder="Type">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">CL</label>
        <input type="text" maxlength="5" class="form-control required amount" name="cl" value="{{ $employee_leave_left->cl }}" id="cl" />
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">ML</label>
        <input type="text" maxlength="5" class="form-control required amount" name="ml" value="{{ $employee_leave_left->ml }}" id="ml"
               placeholder="ML">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">Gate Pass Min</label>
        <input type="text" maxlength="4" class="form-control required numberOnly" name="gate_pass_min" value="{{ $employee_leave_left->gate_pass_min }}" id="gate_pass_min"
               placeholder="Gate Pass Min">
    </div>
    <input type="hidden" value="{{ $employee_leave_left->id }}" name="lid">
    <button type="submit" class="btn btn-success mr-2">Update</button>
</form>
