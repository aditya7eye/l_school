
@extends('master.master') 
@section('title','News | Manage Admin') 
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
                              <h4 class="card-title">Create Admin</h4>
                              <hr>
                              <form class="forms-sample" action="{{ url('insertadmin') }}" method="get">
                                <div class="form-group">
                                  <label for="exampleInputName1">Name</label>
                                  <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail3">Username</label>
                                  <input type="email" class="form-control" name="username" id="username"  placeholder="Username">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword4">Password</label>
                                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                <a href="{{ url('manage-admin') }}"><button type="button" class="btn btn-dark">Cancel</button></a>
                             
                              </form>
                            </div>
                          </div>
<br>
                          <div class="row">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table class="center-aligned-table table table-bordered">
                                                <thead style="background-color: #34BF9B;">
                                                    <tr>
                                                        <th class="border-bottom-0" style="color:white;">#</th>
                                                        <th class="border-bottom-0" style="color:white;">Name</th>
                                                        <th class="border-bottom-0" style="color:white;">Username</th>
                                                        <th class="border-bottom-0" style="color:white;">Password</th>
                                                        <th class="border-bottom-0" style="color:white;">Action</th>
                                                    </tr>
                                                </thead>
                                                @php
$adminlist = \App\Admin_Model::where(['is_del' => 0])->orderBy('id','desc')->get();                                                
                                                @endphp
                                                <tbody>
                                                    @if(count($adminlist) > 0)
                                                    @foreach ($adminlist as $index => $adminlistobj)
                                                    <tr>
                                                            <td># {{ $index+1 }}</td>
                                                            <td>{{ ucwords($adminlistobj->name) }}</td>
                                                            <td>{{ $adminlistobj->username }}</td>
                                                            <td>{{ $adminlistobj->password }}</td>
                                                            <td><button onclick="update_admin({{ $adminlistobj->id }})" class="btn btn-primary ">Edit</button> <button onclick="del_admin({{ $adminlistobj->id }});" class="btn btn-danger ">Delete</button></td>
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