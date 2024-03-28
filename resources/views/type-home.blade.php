@extends('layouts.layout')

@section('title', 'Asset Type Home')

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
                <a href="#" class="btn btn-outline-success" id="addAssets" >Add Assets Type</a>
            </div>
            <!-- Check datatable -->
                <div class="container mt-2">
                    <table id="posts-table" class="table table-hover" >
                    <thead class="table-success">
                        <tr>
                            <th>Sl no</th>
                            <th>Type Description</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assetTypes as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $type->type; }}</td>
                                <td>
                                  <a href="javascript:void(0)" data-type-id="{{ $type->id }}" data-type-val="{{ $type->type }}" id="editAssets" name = "editAssets" class="btn btn-secondary"> Edit </a>
                                </td>
                                <td><a href="javascript:void(0)" id="delete-type" data-type-id="{{ $type->id }}" class="btn btn-danger"> Delete </a></td>
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
        
        <!--Add Type popup -->
        <div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="typeModalLabel">Asset type - Add</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="typeForm">
                    @csrf
                        <!-- Form fields for collecting data -->
                        <input type="text" name="assetType" id="assetType" placeholder="Enter asset type" class="form-control" required>
                        
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>


        <!--Edit Type popup -->
        <div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="EditTypeModalLabel">Asset type - Edit</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="editTypeForm">
                      @csrf
                      @method('PUT')
                        <!-- Form fields for Editing data -->
                        <input type="text" name="editAssetType" id="editAssetType" placeholder="Edit asset type" class="form-control" required>

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
    $('#addAssets').click(function(e) {
      e.preventDefault();
      $('#typeForm')[0].reset(); 
      $('#typeModal').modal('show');
    });

  });

  /**
   * Edit Type
   * 
   */
  $(document).ready(function() {

    $('body').on('click', '#editAssets', function (e) {

        e.preventDefault();
        $('#editTypeForm')[0].reset(); 
        $('#editTypeModal').modal('show');
       
        // Get the value of the asset type
        var typeId = $(this).data('type-val');
        $('#editAssetType').val(typeId);

        // Get the ID of the asset type to be edited
        var typeId = $(this).data('type-id');
        
        // Set the URL for the form action dynamically
        var editUrl = "{{ route('assets-type.update', ['assets_type' => ':id']) }}";
        editUrl = editUrl.replace(':id', typeId);
        $('#editTypeForm').attr('action', editUrl);

    });

    //Update
    $('#editTypeForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
          $('#editTypeModal').modal('hide'); // Close the modal
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
    $('#typeForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('assets-type.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#typeModal').modal('hide'); // Close the modal
          alert(response.message);

                 // Append new <tr> to posts-table with data from response
        var newRow = '<tr>' +
                      '<td>New</td>' +
                      '<td>' + response.type + '</td>' +
                      '<td><a href="javascript:void(0)" class="btn btn-secondary" data-type-id="'+ response.id + '" data-type-val="'+ response.type + '" id="editAssets" name = "editAssets"> Edit </a></td>' +
                      '<td><a href="javascript:void(0)" id="delete-type" data-type-id="'+ response.id + '" class="btn btn-danger"> Delete </a></td>' +
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
      $('body').on('click', '#delete-type', function () {

        // Get the ID of the asset type to be edited
        var typeId = $(this).data('type-id');
        
        // Set the URL for the form action dynamically
        var typeUrl = "{{ route('assets-type.destroy', ['assets_type' => ':id']) }}";
        typeUrl = typeUrl.replace(':id', typeId);

        var trObj = $(this);
        if(confirm("Are you sure, you want to delete this type?") == true){
              $.ajax({
                  url: typeUrl,
                  type: 'DELETE',
                  dataType: 'json',

                  success: function(data) {
                    if (data.error) {
                      alert(data.error);
                      return;
                    }else{
                      alert(data.success);
                      trObj.parents("tr").remove();
                    }
                  }
              });
        }
      });

  });
  
</script>

@endsection
