@extends('layouts.layout')

@section('title', 'Hardware Standard')

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
                <a href="#" class="btn btn-outline-success" id="addAssets" >Add Hardware Standard</a>
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
                        @foreach ($hardwareStandards as $hardwareStandard)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $hardwareStandard->description; }}</td>
                                <td>
                                  <a href="javascript:void(0)" data-hardware-standard-id="{{ $hardwareStandard->id }}" data-hardware-standard-val="{{ $hardwareStandard->description }}" data-type-val="{{ $hardwareStandard->type_id }}" id="editAssets" name = "editAssets" class="btn btn-secondary"> Edit </a>
                                </td>
                                <td><a href="javascript:void(0)" id="delete-hardware" data-hardware-standard-id="{{ $hardwareStandard->id }}" class="btn btn-danger"> Delete </a></td>
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
        <div class="modal fade" id="hardwareModal" tabindex="-1" aria-labelledby="hardwareModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="hardwareModalLabel">Hardware standard - Add</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="hardwareForm">
                    @csrf
                      <label for="assetType">Asset Type:</label>
                      <select id="assetType" name="assetType" class="form-control mb-2" required>
                          <option value="">--Select--</option>
                          @foreach($assetTypes as $assetType)
                              <option value="{{ $assetType->id }}">{{ $assetType->type }}</option>
                          @endforeach
                      </select>
                        <!-- Form fields for collecting data -->
                        <label for="assetHardwareStandard">Hardware Standard:</label>
                        <input type="text" name="assetHardwareStandard" id="assetHardwareStandard" placeholder="Enter hardware standard" class="form-control" required>
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
        <div class="modal fade" id="editHardwareModal" tabindex="-1" aria-labelledby="editHardwareModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editHardwareModalLabel">Hardware standard - Edit</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="editHardwareForm">
                      @csrf
                      @method('PUT')
                      <label for="assetTypeEdit">Asset Type:</label>
                      <select id="assetTypeEdit" name="assetTypeEdit" class="form-control mb-2" required>
                          <option value="">--Select--</option>
                          @foreach($assetTypes as $assetType)
                              <option value="{{ $assetType->id }}">{{ $assetType->type }}</option>
                          @endforeach
                      </select>
                        <!-- Form fields for Editing data -->
                        <label for="editHardwareStandard">Hardware Standard:</label>
                        <input type="text" name="editHardwareStandard" id="editHardwareStandard" placeholder="Edit hardware standard" class="form-control" required>
                        
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
      $('#hardwareForm')[0].reset(); 
      $('#hardwareModal').modal('show');
    });

  });

  /**
   * Edit Type
   * 
   */
  $(document).ready(function() {

    $('body').on('click', '#editAssets', function (e) {

        e.preventDefault();
        $('#editHardwareForm')[0].reset(); 
        $('#editHardwareModal').modal('show');
       
        // Get the value of the asset type
        var typeId = $(this).data('hardware-standard-val');
        $('#editHardwareStandard').val(typeId);

        $('#assetTypeEdit').val($(this).data('type-val'));
        // Get the ID of the asset type to be edited
        var typeId = $(this).data('hardware-standard-id');
        
        // Set the URL for the form action dynamically
        var editUrl = "{{ route('hardware-standard.update', ['hardware_standard' => ':id']) }}";
        editUrl = editUrl.replace(':id', typeId);
        $('#editHardwareForm').attr('action', editUrl);

    });

    //Update
    $('#editHardwareForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
          $('#editHardwareModal').modal('hide'); // Close the modal
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
    $('#hardwareForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('hardware-standard.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#hardwareModal').modal('hide'); // Close the modal
          alert(response.message);

        // Append new <tr> to posts-table with data from response
        var newRow = '<tr>' +
                      '<td>New</td>' +
                      '<td>' + response.type + '</td>' +
                      '<td><a href="javascript:void(0)" class="btn btn-secondary" data-hardware-standard-id="'+ response.id + '"data-type-val="' + response.type_id + '" data-hardware-standard-val="'+ response.type + '" id="editAssets" name = "editAssets"> Edit </a></td>' +
                      '<td><a href="javascript:void(0)" id="delete-hardware" data-hardware-standard-id="'+ response.id + '" class="btn btn-danger"> Delete </a></td>' +
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
      $('body').on('click', '#delete-hardware', function () {

        // Get the ID of the asset type to be edited
        var typeId = $(this).data('hardware-standard-id');
        
        // Set the URL for the form action dynamically
        var typeUrl = "{{ route('hardware-standard.destroy', ['hardware_standard' => ':id']) }}";
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
