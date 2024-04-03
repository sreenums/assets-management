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
                <!--<a href="#" data-asset-id="{{ $asset->id }}" id="history" name="history" class="btn btn-light">Asset Status History</a>-->
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Asset Type</div>
                <div class="col-sm-9">: {{ $asset->type->type }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Hardware Standard</div>
                <div class="col-sm-9">: {{ $asset->hardwareStandard->description }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Technical Specification</div>
                <div class="col-sm-9">: {{ $asset->technicalSpecification->description }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Asset Tag</div>
                <div class="col-sm-9">: {{ $asset->asset_tag }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Serial No</div>
                <div class="col-sm-9">: {{ $asset->serial_no }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Purchasing Order</div>
                <div class="col-sm-9">: {{ $asset->purchase_order }} </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3">Status</div>
                <div class="col-sm-9">: {{ $asset->status_text }} &nbsp;
                    <a href="#" data-asset-id="{{ $asset->id }}" id="changeStatus" name="changeStatus" class="btn btn-secondary ml-2">Change Status</a>
                </div>
            </div>
            <div class="mb-3 row">
                @if(isset($asset->location_id))
                    <div class="col-sm-3">Asset Location</div>
                    <div class="col-sm-9">: {{ $asset->location->name }} </div>
                @endif
                @if(isset($asset->user_id))
                    <div class="col-sm-3">Asset User</div>
                    <div class="col-sm-9">: {{ $asset->user->name }} </div>
                @endif
                
            </div>
            <!-- Add more details here as needed -->
            <!--<a href="{{ route('assets.index') }}" class="btn btn-primary">Back to Assets</a>-->
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header">
            Asset tag: {{ $asset->asset_tag }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Status History Details</h5>
            <ul>
                @foreach($histories as $history)
                    <p><li>{{ $history->description }}, Updated at {{ $history->updated_at_formatted }}</li></p>

                @endforeach
                
            </ul>
        </div>
    </div>
</div>



        <!--Change status popup -->
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
                        <label for="assetStatus">Asset Status:</label>
                        <select id="assetStatus" name="assetStatus" class="form-control mb-2" required>

                            <option value="">--Select--</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $asset->status == $status->id ? 'selected' : '' }} >{{ $status->name }}</option>
                            @endforeach
                        </select>

                        <label for="assetLocationOrUser" class="form-label">User/ Location</label>
                        <select id="assetLocationOrUser" name="assetLocationOrUser" class="form-select" required>
                            
                            @if(isset($asset->location_id))
                            <option selected value="{{ $asset->location_id }}">{{ $asset->location->name }}</option>

                            @endif

                            @if(isset($asset->user_id))
                            <option selected value="{{ $asset->user_id }}">{{ $asset->user->name }}</option>

                            @endif

                        </select>
                        @if ($errors->has('assetLocationOrUser'))
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




        <!-- <div class="modal fade" id="assetHistoryModal" tabindex="-1" aria-labelledby="assetHistoryModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="assetHistoryModalLabel">Asset Status History</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="assetHistoryContainer" style="word-wrap: break-word">

                        <div class="card">
                            <div class="card-header">
                                Asset tag: {{ $asset->asset_tag }}
                            </div>
                            <div class="card-body" >
                                <h5 class="card-title">Status History Details</h5>
                                <p class="card-text" id="assetHistoryDetails">Details about the asset history...</p>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div> -->

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

    
    $(document).on('change','#assetStatus', function() {
      let assetStatus = $(this).val();
      if (assetStatus == '2') {           //Assigned

            //$("#assetLocationOrUser").html("<option value=''>User Selected</option>");
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
                        
                        $("#assetLocationOrUser").html(all_options);
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

                        $("#assetLocationOrUser").html(all_options);
                    }
                }
          });
        } 

      });

    /* $('body').on('click', '#history', function (e) {
        e.preventDefault();
        $('#assetHistoryModal').modal('show');
        var assetId = $(this).data('asset-id');

        // History view with assetId
        loadHistory(assetId);
    }); */

    /*
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
    } */

});


</script>
@endsection