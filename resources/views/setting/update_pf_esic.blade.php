<script src="{{ url('js/validate.js') }}"></script>

<div class="card-body">
    @php
        $data = \App\PFESIC::first();
    @endphp
    <form class="forms-sample" action="{{ url('updatepfesic') }}" method="get">
        <div class="form-group">
            <label for="exampleInputName1">PF%</label>
            <input type="text" maxlength="4" class="form-control required amount" id="pf" value="{{ $data->pf }}" name="pf" placeholder="PF">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail3">ESIC%</label>
            <input type="text" maxlength="4" class="form-control required amount" name="esic" value="{{ $data->esic }}" id="esic" placeholder="ESIC">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail3">Gate Pass Min</label>
            <input type="text" maxlength="4" class="form-control required amount" name="gate_pass_min" value="{{ $data->gate_pass_min }}" id="gate_pass_min" placeholder="Gate Pass Min">
        </div>
        <input type="hidden" value="{{ $data->id }}" name="uuid">
        <button type="submit" class="btn btn-success mr-2">Update</button>
    </form>
</div>