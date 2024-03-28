@extends('layouts.layout')

@section('title', 'View Asset Details')

@section('content')

<br>
<br>
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-4">Asset Informations</h3>
                <a href="#" data-asset-id="{{ $asset->id }}" id="history" name="history" class="btn btn-light">Asset Status History</a>
            </div>
            <div class="mb-3 row">
                <label for="assetType" class="col-sm-3 col-form-label">Asset Type</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="assetType" value="{{ $asset->type->type }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="hardwareStandard" class="col-sm-3 col-form-label">Hardware Standard</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="hardwareStandard" value="{{ $asset->hardwareStandard->description }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="technicalSpec" class="col-sm-3 col-form-label">Technical Specification</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="technicalSpec" value="{{ $asset->technicalSpecification->description }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="assetTag" class="col-sm-3 col-form-label">Asset Tag</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="assetTag" value="{{ $asset->asset_tag }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="assetSlno" class="col-sm-3 col-form-label">Serial No</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="assetSlno" value="{{ $asset->serial_no }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="purchaseOrder" class="col-sm-3 col-form-label">Purchasing Order</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="purchaseOrder" value="{{ $asset->purchase_order }}" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="status" class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="status" value="{{ $asset->status_text }}" readonly>
                    <a href="#" data-asset-id="{{ $asset->id }}" id="changeStatus" name="changeStatus" class="btn btn-secondary mt-2">Change Status</a>
                </div>
            </div>
            <div class="mb-3 row">
                @if(isset($asset->location_id))
                    <label for="assetLocation" class="col-sm-3 col-form-label">Asset Location</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="assetLocation" value="{{ $asset->location->name }}" readonly>
                    </div>
                @endif
                @if(isset($asset->user_id))
                    <label for="assetLocation" class="col-sm-3 col-form-label">Asset User</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="assetLocation" value="{{ $asset->user->name }}" readonly>
                    </div>
                @endif
                
            </div>
            <!-- Add more details here as needed -->
            <a href="{{ route('assets.index') }}" class="btn btn-primary">Back to Assets</a>
        </div>
    </div>
</div>
        <!--Edit Type popup -->
        <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="changeStatusModalLabel">Change Asset Status</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                    <form id="changeStatusForm" method="PUT" action="{{ route('asset.update-status', ['id' => $asset->id]) }}">
                      @csrf
                        <label for="assetStatusChange">Asset Status:</label>
                        <select id="assetStatusChange" name="assetStatusChange" class="form-control mb-2" required>
                            <option value="">--Select--</option>
                            <option value="1" {{ $asset->status == 1 ? 'selected' : '' }}>Brand New</option>
                            <option value="2" {{ $asset->status == 2 ? 'selected' : '' }}>Assigned</option>
                            <option value="3" {{ $asset->status == 3 ? 'selected' : '' }}>Damaged</option>
                        </select>

                        <label for="assetLocationEdit" class="form-label">User/ Location</label>
                        <select id="assetLocationEdit" name="assetLocationEdit" class="form-select" required>
                            
                            @if(isset($asset->location_id))
                            <option selected value="{{ $asset->location_id }}">{{ $asset->location->name }}</option>

                            @endif

                            @if(isset($asset->user_id))
                            <option selected value="{{ $asset->user_id }}">{{ $asset->user->name }}</option>

                            @endif

                        </select>
                        @if ($errors->has('assetLocationEdit'))
                            <div class="validation-error">Please select asset location</div>
                        @endif

                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>




        <div class="modal fade" id="assetHistoryModal" tabindex="-1" aria-labelledby="assetHistoryModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="assetHistoryModalLabel">Asset Status History</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="assetHistoryContainer" style="word-wrap: break-word">
                        <!--<h1 class="mt-5 mb-3">Asset History</h1>-->

                        <!-- Asset history cards -->
                        <div class="card">
                            <div class="card-header">
                                Asset tag: {{ $asset->asset_tag }}
                            </div>
                            <div class="card-body" >
                                <h5 class="card-title">Status History Details</h5>
                                <p class="card-text" id="assetHistoryDetails">Details about the asset history...</p>
                                <!-- Add more details as needed -->
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>

<script>
  $(document).ready(function() {

    $('body').on('click', '#changeStatus', function (e) {

        e.preventDefault();
        $('#changeStatusForm')[0].reset(); 
        $('#changeStatusModal').modal('show');
    
    });

    //Update
    $('#changeStatusForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      
      $.ajax({
        type: 'PUT',
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

    
    $(document).on('change','#assetStatusChange', function() {
      let assetStatusChange = $(this).val();
      if (assetStatusChange == '2') {           //Assigned

            //$("#assetLocationEdit").html("<option value=''>User Selected</option>");
            $.ajax({
                method: 'post',
                url: "{{ route('get.users') }}",

                success: function(res) {
                    if (res.status == 'success') {
                        let all_options = "<option value=''>--Select--</option>"; 
                        let users = res.users;
                        $.each(users, function(index, value) {
                            all_options += "<option value='" + value.id +
                                "'>" + value.name + "</option>";
                        });
                        
                        $("#assetLocationEdit").html(all_options);
                    }
                }
            });
        } else {
          $.ajax({
                method: 'post',
                url: "{{ route('get.locations') }}",

                success: function(res) {
                    if (res.status == 'success') {
                        let all_options = "<option value=''>--Select--</option>";
                        let locations = res.locations; 
                        $.each(locations, function(index, value) {
                            all_options += "<option value='" + value.id +
                                "'>" + value.name + "</option>";
                        });

                        $("#assetLocationEdit").html(all_options);
                    }
                }
          });
        } 

      });

    $('body').on('click', '#history', function (e) {
        e.preventDefault();
        $('#assetHistoryModal').modal('show');
        var assetId = $(this).data('asset-id');

        // History view with assetId
        loadHistory(assetId);
    });

    function loadHistory(assetId) {
        $.ajax({
            url: '/assets/' + assetId + '/history',
            type: 'GET',
            success: function(response) {
                var histories = response.histories;
                var historyHtml = '';
                if (histories.length > 0) {
                    historyHtml += '<ul>';
                    histories.forEach(function(history) {

                        if (history.description !== null && history.description !== undefined ) {
                            var dateObject = new Date(history.updated_at);
                            var options = { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit" };
                            var formattedDate = new Intl.DateTimeFormat('en-GB', options).format(dateObject);
                            if(history.action == 'created'){
                                timeUpdated = ', Created at '+formattedDate;
                            }else{
                                timeUpdated = ', Updated at '+formattedDate;
                            }
                            
                        historyHtml += '<li>' + history.description + timeUpdated + '</li><br>';
                        }

                    });
                    historyHtml += '</ul>';
                } else {
                    historyHtml = 'No History Available.';
                }
                $('#assetHistoryDetails').html(historyHtml);
                $('#assetHistoryModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

});


</script>
@endsection