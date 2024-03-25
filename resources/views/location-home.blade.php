@extends('layouts.layout')

@section('title', 'Location Home')

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
                <a href="#" class="btn btn-outline-success" id="addAssetsLocation" >Add assets location</a>
            </div>
            <!-- Check datatable -->
                <div class="container mt-2">
                    <table id="posts-table" class="table table-hover" >
                    <thead class="table-success">
                        <tr>
                            <th>Sl no</th>
                            <th>Location</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $location->name; }}</td>
                                <td>
                                  <a href="javascript:void(0)" data-location-id="{{ $location->id }}" data-type-val="{{ $location->name }}" id="editAssetsLocation" name = "editAssetsLocation" class="btn btn-secondary"> Edit </a>
                                </td>
                                <td><a href="javascript:void(0)" id="delete-location" data-location-id="{{ $location->id }}" class="btn btn-danger"> Delete </a></td>
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
        
        <!--Add location popup -->
        <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="ModalLabel">Add location</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="locationForm">
                    @csrf
                        <!-- Form fields for collecting data -->
                        <input type="text" name="assetLocation" id="assetLocation" placeholder="Enter location" class="form-control" required>
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


        <!--Edit location popup -->
        <div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editLocationModalLabel">Comments</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="editLocationForm">
                      @csrf
                      @method('PUT')
                        <!-- Form fields for Editing data -->
                        <input type="text" name="editLocation" id="editLocation" placeholder="Edit location" class="form-control" required>
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

      </tbody>

    </table>
    <br>
    <br>

</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#addAssetsLocation').click(function(e) {
      e.preventDefault();
      $('#locationForm')[0].reset(); 
      $('#locationModal').modal('show');
    });

  });

  /**
   * Edit Technical Spec
   * 
   */
  $(document).ready(function() {

    $('body').on('click', '#editAssetsLocation', function (e) {
        
        e.preventDefault();
        $('#editLocationForm')[0].reset(); 
        $('#editLocationModal').modal('show');
       
        // Get the value of technical spec
        var location = $(this).data('type-val');
        $('#editLocation').val(location);

        // Get the ID of technical spec to be edited
        var locationId = $(this).data('location-id');
        
        // Set the URL for the form action dynamically
        var editUrl = "{{ route('locations.update', ['location' => ':id']) }}";
        editUrl = editUrl.replace(':id', locationId);
        $('#editLocationForm').attr('action', editUrl);

    });

    //Update
    $('#editLocationForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
          $('#editLocationModal').modal('hide'); // Close the modal
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
    $('#locationForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('locations.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#locationModal').modal('hide');
          alert(response.message);

                 // Append new <tr> to posts-table with data from response
        var newRow = '<tr>' +
                      '<td>New</td>' +
                      '<td>' + response.type + '</td>' +
                      '<td><a href="javascript:void(0)" class="btn btn-secondary" data-location-id="'+ response.id + '" data-type-val="'+ response.type + '" id="editAssetsLocation" name = "editAssetsLocation"> Edit </a></td>' +
                      '<td><a href="javascript:void(0)" id="delete-location" data-location-id="'+ response.id + '" class="btn btn-danger"> Delete </a></td>' +
                    '</tr>';
        $('#posts-table tbody').append(newRow);
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
      $('body').on('click', '#delete-location', function () {

        // Get the ID of the technical spec to be edited
        var locationId = $(this).data('location-id');
        
        var typeUrl = "{{ route('locations.destroy', ['location' => ':id']) }}";
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
