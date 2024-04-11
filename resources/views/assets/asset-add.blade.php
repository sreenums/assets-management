@extends('layouts.layout')

@section('title', 'Add Asset')

@section('content')
<br>
<div class="container mt-3 pl-5 max-tb-width">
  <br>
  <!--<div class="mt-3">
    <a href="#" class="btn btn-dark">Back</a>
  </div>-->
  <br>

  @if (session('success'))
    <p>
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    </p>
  @endif
  
  <!--<h2>Add New Asset</h2>-->
  <div class="starter-template mt-6 mb-4 d-flex justify-content-between align-items-center">
      <h3>Add New Asset</h3>
      <div class="col-md-3">
          <div class="form-group ms-auto">
            <a href="{{ route('upload.form.csv') }}" class="btn btn-light"><i class="bi bi-file-earmark-arrow-up"></i> Upload Asset (.csv)</a>
          <!--<button id="upload-button" class="btn btn-light"
                  data-upload-route="{{ route('upload.form.csv') }}" onclick="submitForm()">
              <i class="bi bi-file-earmark-arrow-up"></i> Upload Asset (.csv)
          </button>-->
          </div>
      </div>
  </div>

  <!--Asset CSV Upload popup -->
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Add Assets - CSV File Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="uploadContainer">
              <form id="fileUploadForm" name="fileUploadForm" action="{{ route('upload.asset.csv') }}" method="POST" enctype="multipart/form-data">
              @csrf
                  <!-- Form fields for collecting data -->
                  <!--<label class="form-label" for="importCsv">Upload</label>-->
                  <input type="file" class="form-control" name="importCsv" id="importCsv" accept=".csv">
                  
                  
                  <button type="submit" class="btn btn-primary mt-3">Upload</button>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

<!--Asset Upload Validation Error Popup -->
  <div class="modal fade" id="errorMessageModal" tabindex="-1" aria-labelledby="errorMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorMessageModalLabel">Upload Validation Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="assetHistoryContainer" style="word-wrap: break-word">

                  <div class="card">
                      <div class="card-body" >
                          <h5 class="card-title"><span class="validation-error-header mb-2" id="validationHead" name="validationHead" style="color: red;"></span></h5>
                          <p class="card-text mt-4" id="errorMessageDetails" >Details about the validation error...</p>
                      </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>


  <form method="POST" id="assetForm" class="was-validated" style="border: 1px solid #ccc; padding: 20px;" action="{{ route('assets.store'); }}" enctype="multipart/form-data">
    @csrf
    <div class="col-md-6">
      <label for="assetType" class="form-label">Type</label>      
      <select id="assetType" name="assetType" class="form-select" required>
        <option selected value="">--Select-- </option>
        @foreach ($assetTypes as $assetType)
        <option value="{{ $assetType->id }} ">{{ $assetType->type }} </option>
        @endforeach
      </select>
      @if ($errors->has('assetType'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="col-md-6 mt-2">
      <label for="hardwareStandard" class="form-label">Hardware Standard</label>
      <select id="hardwareStandard" name="hardwareStandard" class="form-select" required>
        <option selected value="">--Select Asset Type-- </option>
      </select>

      @if ($errors->has('hardwareStandard'))
        <div class="validation-error">Please select hardware standard.</div>
      @endif
    </div>

    <div class="col-md-6 mt-2">
      <label for="technicalSpec" class="form-label">Technical Specification</label>
      <select id="technicalSpec" name="technicalSpec" class="form-select" required>
        <option selected value="">--Select Hardware Standard-- </option>
      </select>

      @if ($errors->has('technicalSpec'))
        <div class="validation-error">Please enter technical specification.</div>
      @endif
    </div>

    <div class="col-md-6 mt-2">
      <label for="assetLocation" class="form-label">Location</label>
      <select id="assetLocation" name="assetLocation" class="form-select" required>
        
        <option selected value="">--Select-- </option>
        @foreach ($assetLocations as $assetLocation)
        <option value="{{ $assetLocation->id }} ">{{ $assetLocation->name }} </option>
        @endforeach

      </select>
      @if ($errors->has('assetLocation'))
        <div class="validation-error">Please select asset location</div>
      @endif
    </div>

    <div class="col-md-5 mt-2">
      <label for="assetTag" class="form-label">Asset tag</label>
      <input type="text" class="form-control" id="assetTag" name="assetTag" maxlength="150" required>
      @if ($errors->has('assetTag'))
        <div class="validation-error">{{ $errors->first('assetTag') }}</div>
      @endif
    </div>

    <div class="col-md-5 mt-2">
      <label for="serialNo" class="form-label">Serial No</label>
      <input type="text" class="form-control" id="serialNo" name="serialNo" maxlength="150" required>
      @if ($errors->has('serialNo'))
        <div class="validation-error">{{ $errors->first('serialNo') }}</div>
      @endif
    </div>

    <div class="col-md-5 mt-2">
      <label for="purchasingOrder" class="form-label">Purchasing order</label>
      <input type="text" class="form-control" id="purchasingOrder" name="purchasingOrder" maxlength="150" required>
      @if ($errors->has('purchasingOrder'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="col-md-3 mt-2">
      <label for="assetStatus" class="form-label">Status</label>
      <select id="assetStatus" name="assetStatus" class="form-select" required>
        <option value="">--Select--</option>
        <option value="1" selected>Brand New</option>
        <!--<option value="2">Assigned</option>
        <option value="3">Damaged</option>-->
      </select>
    </div>

    @if(isset($categories) && $categories != '[]')
    <div class="col-md-6 mt-2">
      Categories
      <div class="row">
          @foreach ($categories as $category)
          <div class="col">
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->id }}" name="categories[]" >
                  <label class="form-check-label" for="{{ $category->id }}">{{ $category->category }}</label>
              </div>
          </div>
          @endforeach
      </div>
    </div>
    @endif

    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>    
</div>



<script type="text/javascript">

  $(document).ready(function() {
    // $('#upload-button').click(function(e) {
    //   e.preventDefault();
    //   $('#validationHead').text("");
    //   $('#fileUploadForm')[0].reset(); 
    //   $('#uploadModal').modal('show');
    // });

    //Upload
    $('#fileUploadForm').submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response.error) {
            //alert('Records not updated from row ' +response.row +', '+ response.errorMessage+'\n'+'Correct Errors and Try Again!');
            var errorMessages = response.errorMessage;
            var errorHtml = '';

            errorHtml += '<ul>';
            $('#validationHead').text("Records not uploaded from row - '" +response.row+"'.. Correct Errors and Try Again!");
            errorMessages.forEach(function(history) {
              errorHtml += '<li><span >' + history.trim() + '</span></li><br>';
            });
            errorHtml += '</ul>';
            $('#errorMessageDetails').html(errorHtml);
            $('#errorMessageModal').modal('show');

            return;

          }else if(response.fileTypeError){

            alert(response.errorMessage);
            return;
            
          }
          else{
            $('#uploadModal').modal('hide'); // Close the modal
            alert(response.message);
            window.location.reload();
          }
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });

    });




  });



  /**
   * Get the list of hardware standards for asset type
   */
  $(document).ready(function(){
      $(document).on('change','#assetType', function() {
        $("#hardwareStandard").html("<option value=''>--Select--</option>");
        $("#technicalSpec").html("<option value=''>--Select--</option>");
          let assetType = $(this).val();
          //$('#subcategory_info').show();
          $.ajax({
              method: 'post',
              url: "{{ route('get.type.hardwares') }}",
              data: {
                assetType: assetType
              },
              success: function(res) {
                  if (res.status == 'success') {
                      let all_options = "<option value=''>--Select--</option>";
                      let subHardwareStandards = res.subHardwareStandards;
                      $.each(subHardwareStandards, function(index, value) {
                          all_options += "<option value='" + value.id +
                              "'>" + value.description + "</option>";
                      });

                      $("#hardwareStandard").html(all_options);
                  }
              }
          })
      });
  });

  /**
   * Get the list of technical specs for hardware standard
   */
  $(document).ready(function(){
      $(document).on('change','#hardwareStandard', function() {
          $("#technicalSpec").html("<option value=''>--Select--</option>");
          let hardwareStandard = $(this).val();
          
          $.ajax({
              method: 'post',
              url: "{{ route('get.hardware.technical.spec') }}",
              data: {
                hardwareStandard: hardwareStandard
              },
              success: function(res) {
                  if (res.status == 'success') {
                      let all_options = "<option value=''>--Select--</option>";
                      let subTechnicalSpecs = res.subTechnicalSpecs;
                      $.each(subTechnicalSpecs, function(index, value) {
                          all_options += "<option value='" + value.id +
                              "'>" + value.description + "</option>";
                      });

                      $("#technicalSpec").html(all_options);
                  }
              }
          })
      });


      // $(document).on('change','#user', function() {
      // let user = $(this).val(); //alert(user);
      // if (user !== '') {
      //     //$("#assetLocation").html("<option value=''>User Selected</option>");
      //     $("#assetLocation").prop('disabled', true); 
      // } else {
      //     // If no user is selected, remove readonly
      //     $("#assetLocation").prop('disabled', false);
      // }
      // });


      // $(document).on('change','#assetLocation', function() {
      // let assetLocation = $(this).val();
      // if (assetLocation !== '') {
      //     $("#user").prop('disabled', true); 
      // } else {
      //     $("#user").prop('disabled', false);
      // }
      // });


  });

    //Form submit for csv export
    function submitForm() {
      //alert('submit exit');
      return false;
      //  document.getElementById('searchForm').submit();
    }
</script>



@endsection