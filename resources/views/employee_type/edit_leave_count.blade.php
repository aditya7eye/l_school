<script src="{{ url('js/validate.js') }}"></script>

<form class="forms-sample" action="{{ url('update_leave_count') }}" method="get">
    <div class="form-group">
        <label for="exampleInputName1">Type</label>
        <input type="text" maxlength="4" class="form-control" id="emp_type" value="{{ $emp_type->type }}" name="pf" onkeypress="return false;" onkeydown="return false;" placeholder="Type">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">CL</label>
        <input type="text" maxlength="5" class="form-control required amount" name="cl" value="{{ $emp_type->cl }}" id="cl" />
    </div>
    <div class="form-group">
        <label for="exampleInputEmail3">ML</label>
        <input type="text" maxlength="5" class="form-control required amount" name="ml" value="{{ $emp_type->ml }}" id="ml"
               placeholder="ML">
    </div>
    <input type="hidden" value="{{ $emp_type->id }}" name="lid">
    <button type="submit" class="btn btn-success mr-2">Update</button>
</form>
