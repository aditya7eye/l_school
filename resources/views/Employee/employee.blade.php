
@extends('master.master') 
@section('title','L.K.S.S.S. | Manage Employee') 
@section('content')
<style>
.mybg{
   padding:10px 10px; 
}
</style>

<div class="container-fluid page-body-wrapper" id="maindiv">
    <div class="main-panel">
        <div class="content-wrapper">
               

                    <div class="card">
                            <div class="card-body">
                              <h4 class="card-title">Create Plan</h4>
                              <hr>
                              <form class="forms-sample" action="{{ url('insertadmin') }}" method="get">
                                <div class="form-group">
                                  <label for="exampleInputName1">Plan Name</label>
                                  <input type="text" class="form-control" id="p_name" name="p_name" placeholder="Plan Name">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail3">Validity</label>
                                  <input type="text" class="form-control" name="validity" id="validity"  placeholder="Validity">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Type</label>
                                    {{-- <input type="text" class="form-control" name="validity" id="validity"  placeholder="Validity"> --}}
                                    <select name="type" id="type" class="form-control">
                                        <option value="Days">Days</option>
                                        <option value="Months">Months</option>
                                    </select>
                                  </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword4">Price</label>
                                  <input type="password" class="form-control" id="price" name="price" placeholder="Price">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword4">Offer Price</label>
                                    <input type="password" class="form-control" id="o_price" name="o_price" placeholder="Offer Price">
                                  </div>
                                <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                <a href="{{ url('add-admin') }}"><button type="button" class="btn btn-dark">Cancel</button></a>
                             
                              </form>
                            </div>
                          </div>
<br>
                          <div class="row">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table class="center-aligned-table table table-bordered">
                                                <thead style="background-color: #3506065e;">
                                                    <tr>
                                                        <th class="border-bottom-0" style="color:white;">#</th>
                                                        <th class="border-bottom-0" style="color:white;">EmployeeName</th>
                                                        <th class="border-bottom-0" style="color:white;">Date Of Joining</th>
                                                        <th class="border-bottom-0" style="color:white;">Session</th>
                                                        <th class="border-bottom-0" style="color:white;">PF</th>
                                                        <th class="border-bottom-0" style="color:white;">Action</th>
                                                    </tr>
                                                </thead>
                                                @php
$employeelist = \App\EmployeeModel::where(['RecordStatus'=>1])->get();                                                
                                                @endphp
                                                <tbody>
                                                    @if(count($employeelist) > 0)
                                                    @foreach ($employeelist as $index => $employeelistobj)
                                                    <tr>
                                                            <td># {{ $index+1 }}</td>
                                                            <td>{{ ucwords($employeelistobj->EmployeeName) }}</td>
                                                            <?php
$date=date_create($employeelistobj->DOJ,timezone_open("Asia/Kolkata"));
$mydate = date_format($date,"d-M-Y");
?>
                                                            <td>{{ $mydate }}</td>
                                                            <td></td>
                                                            <td>{{ $employeelistobj->password }}</td>
                                                            <td><button class="btn btn-primary">delete</button></td>
                                                          
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                            <td align="center" colspan="5">No Record Found</td>
                                            
                                                    </tr>
                                                    @endif
                                                   
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
         
                     
                </div>
            </div>
        </div>
        

        <script>$(window).scroll(function() {
            var headerBottom = '.navbar.horizontal-layout .nav-bottom';
            if ($(window).scrollTop() >= 70) {
                $(headerBottom).addClass('fixed-top');
            } else {
                $(headerBottom).removeClass('fixed-top');
            }
        });</script>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
       
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
</div>

<script>
function del_admin(id)
{
Swal({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.value) {
    $.get('{{ url('del_admin') }}', {
                  did:id
                }, function (data) {
                    $("#maindiv").load(location.href + " #maindiv");
                    Swal(
      'Deleted!',
      'Your file has been deleted.',
      'success'
    )
                });
    
  }
})
}

function update_admin(id)
{
    $.get('{{ url('update_admin_form') }}', {
                  uid:id
                }, function (data) {
                    $('#mh').html("Edit Admin Detail's");
                    $('#mb').html(data);
                  $('#myModal').modal('show');
                });
}
</script>

@stop