@extends('layouts.layout')

@section('title', 'User Home')

@section('content')

<div class="container mt-3">
    <br>

    <form method="POST" id="searchPosts" name="searchPosts" action="#" >
      @csrf
      <div class="container mt-2">

        <!-- Search Results Section -->
        <div class="row mt-3">
            <div class="col-md-8">
            <div class="text-right mt-5">
                <a href="#" class="btn btn-outline-success" id="addUser" >Add user</a>
            </div>
            <!-- Check datatable -->
                <div class="container mt-2">
                    <table id="users-table" class="table table-hover" >
                    <thead class="table-success">
                        <tr>
                            <th>Sl no</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name; }}</td>
                                <td>{{ $user->email; }}</td>
                                <td>
                                  <a href="javascript:void(0)" data-user-id="{{ $user->id }}" data-type-val="{{ $user->name }}" data-email="{{ $user->email }}" id="editUser" name = "editUser" class="btn btn-secondary"> Edit </a>
                                </td>
                                <td><a href="javascript:void(0)" id="delete-user" data-user-id="{{ $user->id }}" class="btn btn-danger"> Delete </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </form>

    <br>
    <table class="table table-hover">

      <tbody>
        
        <!--Add Hardware Standard popup -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="ModalLabel">Comments</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="userForm">
                    @csrf
                        <!-- Form fields for collecting data -->
                        <label for="assetUser" class="form-label">Name:</label>
                        <input type="text" name="assetUser" id="assetUser" placeholder="Enter user name" class="form-control" required>
                        <label for="email" class="form-label mt-2">Email Id:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id" required>
                        <!-- Add more fields as needed -->
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>


        <!--Edit user popup -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editUserModalLabel">Comments</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="editUserForm">
                      @csrf
                      @method('PUT')
                        <!-- Form fields for Editing data -->
                        <label for="editUserName" class="form-label mt-2">User name:</label>
                        <input type="text" id="editUserName" name="editUserName" placeholder="Edit user name" class="form-control" required>
                        <label for="editEmail" class="form-label mt-2">Email Id:</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" placeholder="Enter Email Id" required>
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>

      </tbody>

    </table>
    <br>
    <br>

</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#addUser').click(function(e) {
      e.preventDefault();
      $('#userForm')[0].reset(); 
      $('#userModal').modal('show');
    });

  });

  /**
   * Edit Technical Spec
   * 
   */
  $(document).ready(function() {

    $('body').on('click', '#editUser', function (e) {
        
        e.preventDefault();
        $('#editUserForm')[0].reset(); 
        $('#editUserModal').modal('show');
       
        // Get the value of technical spec
        //var user = $(this).data('type-val');
        $('#editUserName').val($(this).data('type-val'));
        $('#editEmail').val($(this).data('email'));

        // Get the ID of technical spec to be edited
        var locationId = $(this).data('user-id');
        
        // Set the URL for the form action dynamically
        var editUrl = "{{ route('users.update', ['user' => ':id']) }}";
        editUrl = editUrl.replace(':id', locationId);
        $('#editUserForm').attr('action', editUrl);

    });

    //Update
    $('#editUserForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
          $('#editUserModal').modal('hide'); // Close the modal
          alert(response.message);
          window.location.reload();
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });

    });


  });



  $(document).ready(function() {
    $('#userForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('users.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#userModal').modal('hide');
          alert(response.message);

                 // Append new <tr> to users-table with data from response
        var newRow = '<tr>' +
                      '<td>New</td>' +
                      '<td>' + response.type + '</td>' +
                      '<td>' + response.emailId + '</td>' +
                      '<td><a href="javascript:void(0)" class="btn btn-secondary" data-user-id="'+ response.id + '"data-email="'+ response.emailId + '" data-type-val="'+ response.type + '" id="editUser" name = "editUser"> Edit </a></td>' +
                      '<td><a href="javascript:void(0)" id="delete-user" data-user-id="'+ response.id + '" class="btn btn-danger"> Delete </a></td>' +
                    '</tr>';
        $('#users-table tbody').append(newRow);
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });
    });

  });



  $(document).ready(function () {

      //Delete Post
      $('body').on('click', '#delete-user', function () {

        // Get the ID of the technical spec to be edited
        var locationId = $(this).data('user-id');
        
        var typeUrl = "{{ route('users.destroy', ['user' => ':id']) }}";
        typeUrl = typeUrl.replace(':id', locationId);

        var trObj = $(this);
        if(confirm("Are you sure, you want to delete this type?") == true){
              $.ajax({
                  url: typeUrl,
                  type: 'DELETE',
                  dataType: 'json',

                  success: function(data) {
                      alert(data.success);
                      trObj.parents("tr").remove();
                  }
              });
        }
      });

  });
  
</script>

@endsection
