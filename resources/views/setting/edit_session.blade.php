<script src="{{ url('js/validate.js') }}"></script>

<form action="{{url('update_session')}}" id="holiday" method="post" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Session</label>
            <input type="text" disabled class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="Session" value="{{$ses->session}}" maxlength="50">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Start Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="End Date"
                   value="{{ date_format(date_create($ses->start_date), "d-M-Y")}}"

                   maxlength="50">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">End Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="Date" value="{{ date_format(date_create($ses->end_date), "d-M-Y")}}"

                   maxlength="50">
        </div>
    </div>
    <div class="col-sm-12">
    <div class="form-group">
        <label for="">Session Activation</label><br>
        <input type="radio" {{ $ses->is_active == '1'?'checked':'' }} name="is_active" value="1" checked>
        Active
        &nbsp;&nbsp;
        <input type="radio" {{ $ses->is_active == '0'?'checked':'' }} name="is_active" value="0">
        Inactive<br>
    </div>
    </div>
    <input type="hidden" value="{{ $ses->id }}" name="sid">

    <div class="col-sm-12">
        <div class="form-group">
            <button class="btn btn-info btn-sm" type="submit">Submit</button>
        </div>
    </div>
</form>
<script>
    $('.dtp').datepicker({
        format: "dd-M-yyyy",
        maxViewMode: 2,
        // endDate: '-18y',
        daysOfWeekHighlighted: "0",
        autoclose: true
    });
</script>