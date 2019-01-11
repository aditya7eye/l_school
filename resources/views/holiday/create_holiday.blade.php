<script src="{{ url('assets/js/validate.js') }}"></script>

<form action="{{url('size')}}" method="post" id="category" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-12">
        <div class="form-group">
            <label for="username">Size Name</label>
            <input type="text" class="form-control required"  id="cname" name="cname"
                   autocomplete="off" placeholder="Size Name Eg. XL"
                   maxlength="50">
        </div>
    </div>
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-info btn-sm" type="submit">Submit</button>
            </div>
        </div>
</form>