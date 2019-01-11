{{--<script src="{{ url('assets/js/validate.js') }}"></script>--}}
{{--<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>--}}
{{--<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />--}}
<form action="{{url('holiday')}}" method="post" id="category" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="Date"
                   maxlength="50">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Occasion</label>
            <input type="text" class="form-control required" id="datepicker" name="occasion"
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

<script>
    $('.dtp').datepicker({
        format: "dd-M-yyyy",
        maxViewMode: 2,
        // endDate: '-18y',
        daysOfWeekHighlighted: "0",
        autoclose: true,
    });
</script>