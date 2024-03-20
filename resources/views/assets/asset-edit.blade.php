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
  
  <h2>Edit Asset</h2>


  <form method="POST" id="assetForm" class="was-validated" style="border: 1px solid #ccc; padding: 20px;" action="#" enctype="multipart/form-data">
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
      <label for="user" class="form-label">User</label>
      <select id="user" name="user" class="form-select" required>
        <option selected value="">--Select-- </option>

        @foreach ($users as $user)
        <option value="{{ $user->id }} ">{{ $user->name }} </option>
        @endforeach

      </select>
      @if ($errors->has('user'))
        <div class="validation-error">Please select user.</div>
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
        <div class="validation-error">Please select date</div>
      @endif
    </div>

    <div class="col-md-5 mt-2">
      <label for="assetTag" class="form-label">Asset tag</label>
      <input type="text" class="form-control" id="assetTag" name="assetTag" maxlength="150" required>
      @if ($errors->has('assetTag'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="col-md-5 mt-2">
      <label for="serialNo" class="form-label">Serial No</label>
      <input type="text" class="form-control" id="serialNo" name="serialNo" maxlength="150" required>
      @if ($errors->has('serialNo'))
        <div class="validation-error">Please fill out this field.</div>
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
        <option selected value="">--Select--</option>
        <option value="1">Brand New</option>
        <option value="2">Assigned</option>
        <option value="3">Damaged</option>
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


  });

</script>

@endsection