@extends('layouts.layout')

@section('title', 'Technical Specifications Home')

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
                <a href="#" class="btn btn-outline-success" id="addAssets" >Add Technical Specifications</a>
            </div>
            <!-- Check datatable -->
                <div class="container mt-2">
                    <table id="posts-table" class="table table-hover" >
                    <thead class="table-success">
                        <tr>
                            <th>Sl no</th>
                            <th>Specification</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($technicalSpecs as $technicalSpec)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $technicalSpec->description; }}</td>
                                <td>
                                  <a href="javascript:void(0)" data-type-id="{{ $technicalSpec->id }}" data-type-val="{{ $technicalSpec->description }}" id="editAssets" data-hardware="{{ $technicalSpec->hardware_standard_id }}" name = "editAssets" class="btn btn-secondary"> Edit </a>
                                </td>
                                <td><a href="javascript:void(0)" id="delete-technicalSpec" data-type-id="{{ $technicalSpec->id }}" class="btn btn-danger"> Delete </a></td>
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
        <div class="modal fade" id="technicalSpecModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="ModalLabel">Technical Specification - Add</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="technicalSpecForm">
                    @csrf
                      <label for="hardwareStandard">Hardware Standard:</label>
                      <select id="hardwareStandard" name="hardwareStandard" class="form-control mb-2" required>
                          <option value="">--Select--</option>
                          @foreach($hardwareStandards as $hardwareStandard)
                              <option value="{{ $hardwareStandard->id }}">{{ $hardwareStandard->description }}</option>
                          @endforeach
                      </select>
                        <!-- Form fields for collecting data -->
                        <label for="tecnicalSpec">Technical Specification:</label>
                        <input type="text" name="tecnicalSpec" id="tecnicalSpec" placeholder="Enter technical specification" class="form-control" required>
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
        <div class="modal fade" id="editTechnicalSpecModal" tabindex="-1" aria-labelledby="editTechnicalSpecModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="editTechnicalSpecModalLabel">Technical Specification - Edit</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="editTechnicalSpecForm">
                      @csrf
                      @method('PUT')

                      <label for="editHardwareStandard">Hardware Standard:</label>
                      <select id="editHardwareStandard" name="editHardwareStandard" class="form-control mb-2" required>
                          <option value="">--Select--</option>
                          @foreach($hardwareStandards as $hardwareStandard)
                              <option value="{{ $hardwareStandard->id }}">{{ $hardwareStandard->description }}</option>
                          @endforeach
                      </select>
                        <!-- Form fields for Editing data -->
                        <label for="editTechnicalSpec">Technical Specification:</label>
                        <input type="text" name="editTechnicalSpec" id="editTechnicalSpec" placeholder="Edit technical specification" class="form-control" required>
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
    $('#addAssets').click(function(e) {
      e.preventDefault();
      $('#technicalSpecForm')[0].reset(); 
      $('#technicalSpecModal').modal('show');
    });

  });

  /**
   * Edit Technical Spec
   * 
   */
  $(document).ready(function() {

    $('body').on('click', '#editAssets', function (e) {

        e.preventDefault();
        $('#editTechnicalSpecForm')[0].reset(); 
        $('#editTechnicalSpecModal').modal('show');
       
        // Get the value of technical spec
        var typeId = $(this).data('type-val');
        $('#editTechnicalSpec').val(typeId);

        // Get the Hardware standard
        $('#editHardwareStandard').val($(this).data('hardware'));

        // Get the ID of technical spec to be edited
        var typeId = $(this).data('type-id');
        
        // Set the URL for the form action dynamically
        var editUrl = "{{ route('technical-specs.update', ['technical_spec' => ':id']) }}";
        editUrl = editUrl.replace(':id', typeId);
        $('#editTechnicalSpecForm').attr('action', editUrl);

    });

    //Update
    $('#editTechnicalSpecForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
          $('#editTechnicalSpecModal').modal('hide'); // Close the modal
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
    $('#technicalSpecForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: "{{ route('technical-specs.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#technicalSpecModal').modal('hide');
          alert(response.message);

                 // Append new <tr> to posts-table with data from response
        var newRow = '<tr>' +
                      '<td>New</td>' +
                      '<td>' + response.type + '</td>' +
                      '<td><a href="javascript:void(0)" class="btn btn-secondary" data-type-id="'+ response.id + '"data-hardware="'+ response.hardwareId + '" data-type-val="'+ response.type + '" id="editAssets" name = "editAssets"> Edit </a></td>' +
                      '<td><a href="javascript:void(0)" id="delete-technicalSpec" data-type-id="'+ response.id + '" class="btn btn-danger"> Delete </a></td>' +
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
      $('body').on('click', '#delete-technicalSpec', function () {

        // Get the ID of the technical spec to be edited
        var typeId = $(this).data('type-id');
        
        var typeUrl = "{{ route('technical-specs.destroy', ['technical_spec' => ':id']) }}";
        typeUrl = typeUrl.replace(':id', typeId);

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
