<script src="{{ url('assets/js/validate.js') }}"></script>

<form action="{{url('holiday/update')}}" id="holiday" method="post" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Date</label>
            <input type="text" class="form-control dtp required" id="datepicker" name="date"
                   autocomplete="off" placeholder="Date" value="{{ date_format(date_create($holiday->date), "d-M-Y")}}"

                   maxlength="50">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Occasion</label>
            <input type="text" class="form-control required" id="datepicker" name="occasion"
                   autocomplete="off" placeholder="Occasion" value="{{$holiday->occasion}}"
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
        autoclose: true
    });
</script>