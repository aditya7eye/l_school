<script src="{{ url('js/validate.js') }}"></script>

<form action="{{url('save_session')}}" id="holiday" method="post" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Session</label>
            <input type="text" disabled class="form-control required" name="session_name" autocomplete="off" placeholder="Session Name"
                   maxlength="20">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Start Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="End Date">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">End Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="Date">
        </div>
    </div>
    {{--<div class="col-sm-12">--}}
        {{--<div class="form-group">--}}
            {{--<label for="">Session Activation</label><br>--}}
            {{--<input type="radio" name="is_active" value="1" checked>--}}
            {{--Active--}}
            {{--&nbsp;&nbsp;--}}
            {{--<input type="radio" name="is_active" value="0">--}}
            {{--Inactive<br>--}}
        {{--</div>--}}
    {{--</div>--}}

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