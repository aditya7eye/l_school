<div class="card-body">
    <form class="forms-sample" action="{{ url('updateadmindetails') }}" method="get">
      <div class="form-group">
        <label for="exampleInputName1">Name</label>
        <input type="text" class="form-control" id="name" value="{{ $data->name }}" name="name" placeholder="Name">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail3">Username</label>
        <input type="email" class="form-control" name="username" value="{{ $data->username }}" id="username"  placeholder="Username">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword4">Password</label>
        <input type="password" class="form-control" id="password" value="{{ $data->password }}" name="password" placeholder="Password">
      </div>
      <input type="hidden" value="{{ $data->id }}" name="uuid">
      <button type="submit" class="btn btn-warning mr-2">Update</button>
      <a href="{{ url('manage-admin') }}"><button type="button" class="btn btn-dark">Cancel</button></a>
   
    </form>
  </div>