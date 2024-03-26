@extends('layouts.layout')

@section('title', 'Assets Dashboard')

@section('content')

<br>

  <main role="main" class="container mt-5">

    <div class="starter-template mt-6 mb-4">
        <h1>Dashboard Home</h1>
        <!--
      <h1>Bootstrap starter template</h1>
      <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
        -->
    </div>
    <div class="text-right mt-3">
      <a href="{{ route('assets.create'); }}" class="btn btn-outline-success">Add Asset</a>
    </div>
    <div class="table-responsive ml-2 mr-2">
            <div class="row mb-2 mt-4">
                
                <div class="col-md-4">
                  <!-- Asset Type Filter -->
                  <div class="form-group">
                      <label for="assetsType">Type:</label>
                      <select id="assetsType" name="assetsType" class="form-control">
                          <option value="all">All Asset Types</option>
                          @foreach($assetTypes as $assetType)
                              <option value="{{ $assetType->id }}" >{{ $assetType->type }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <!-- Hardware Standard Filter -->
                  <div class="form-group">
                      <label for="hardwareStandard">Hardware Standard:</label>
                      <select id="hardwareStandard" name="hardwareStandard" class="form-control">
                          <option value="all">--Select--</option>
                          @foreach($hardwareStandards as $hardwareStandard)
                              <option value="{{ $hardwareStandard->id }}" >{{ $hardwareStandard->description }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <!-- Hardware Standard Filter -->
                  <div class="form-group">
                      <label for="technicalSpec">Technical Specification:</label>
                      <select id="technicalSpec" name="technicalSpec" class="form-control">
                          <option value="all">--Select--</option>
                          @foreach($technicalSpecs as $technicalSpec)
                              <option value="{{ $technicalSpec->id }}" >{{ $technicalSpec->description }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                    <!-- Status Filter -->
                    <div class="form-group">
                        <label for="assetStatus">Status:</label>
                        <select id="assetStatus" name="assetStatus" class="form-control">
                            <option value="all">All Statuses</option>
                            <option value="1" >Brand New</option>
                            <option value="2" >Assigned</option>
                            <option value="3" >Damaged</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Search Input -->
                    <div class="form-group">
                        <label for="assetSearch">Asset tag/ Asset Slno:</label>
                        <input type="text" id="assetSearch" name="assetSearch" class="form-control" onkeypress="return /[a-zA-Z0-9]/.test(event.key)" placeholder="Asset tag or asset slno" value="{{ request('commentsCount') }}">
                    </div>
                </div>
            </div>

<br>
        <table id="assets-table" class="table table-striped table-hover" >
            <thead class="table-success">
                
                    <tr>
                        <th>Sl no</th>
                        <th><a href="{{ route('assets-type.index') }}">Type</a></th>
                        <th><a href="{{ route('hardware-standard.index') }}">Hardware Standard</a></th>
                        <th><a href="{{ route('technical-specs.index') }}">Technical Specification</a></th>
                        <th><a href="{{ route('users.index'); }}">User</a> / <a href="{{ route('locations.index'); }}">Location</a></th>
                        <th>Status</th>
                        <th>Asset Tag</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
            </thead>
        </table>
        <br>
        <br>
        <br>
    </div>

  </main>

<script type="text/javascript">
    
    $(document).ready(function() {

        $(document).on('change','#assetsType', function() {
        $("#hardwareStandard").html("<option value='all'>--Select--</option>");
        //$("#technicalSpec").html("<option value=''>--Select--</option>");
          let assetType = $(this).val();
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


    $(document).ready(function() {

        $('#assets-table').DataTable({
            columnDefs: [
                { targets: [1, 2, 3, 4, 5, 6, 7, 8, 9], orderable: false } // Disable sorting for all columns except Sl no (index 0)
            ],
            searching: false,
            processing: true,
            serverSide: true,
            ajax: "{{ route('list.asset') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'type', name: 'type' },
                { data: 'hardware_standard', name: 'hardware_standard' },
                { data: 'technicalSpecification', name: 'technical_specification_id' },
                { data: 'location', name: 'location_id' },
                { data: 'status', name: 'status' },
                { data: 'assetTag', name: 'asset_tag' },
                {
                    data: 'id',
                    name: 'view',
                    render: function(data, type, row) {
                            return '<a href="{{ route("assets.show", ["asset" => ":assetId"]) }}" id="view-user" class="btn btn-primary"> View </a>'.replace(':assetId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'edit',
                    render: function(data, type, row) {
                            return '<a href="{{ route("assets.edit", ["asset" => ":assetId"]) }}" id="edit-user" class="btn btn-secondary"> Edit </a>'.replace(':assetId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'delete',
                    render: function(data, type, row) {
                            return '<a href="javascript:void(0)" id="delete-asset" data-url="{{ route("assets.destroy", ["asset" => ":assetId"]) }}" class="btn btn-danger"> Delete </a>'.replace(':assetId', row.id);
                    }
                }
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html(index + 1);
            }
        });


        function handleFilterChange() {
            var assetTypeId = $('#assetsType').val();
            var hardwareStandard = $('#hardwareStandard').val();
            var technicalSpec = $('#technicalSpec').val();
            var statusId = $('#assetStatus').val();
            var assetSearch = $('#assetSearch').val();

            var url = "{{ route('list.asset') }}?";

            if (assetTypeId) {
                url += "assetType=" + assetTypeId + "&";
            }
            if (hardwareStandard) {
                url += "hardwareStandard=" + hardwareStandard + "&";
            }
            if (technicalSpec) {
                url += "technicalSpec=" + technicalSpec + "&";
            }
            if (statusId) {
                url += "status=" + statusId + "&";
            }
            if (assetSearch) {
                url += "assetSearch=" + assetSearch + "&";
            }

            // Remove trailing '&' if exists
            url = url.replace(/&$/, "");

            $('#assets-table').DataTable().ajax.url(url).load();
        }

        $('#assetSearch, #assetStatus, #assetsType, #hardwareStandard, #technicalSpec').on('keyup change', function() {
            
            if ($(this).is('#assetsType')) {
                $("#hardwareStandard").html("<option value='all'>--Select--</option>");
                $("#technicalSpec").html("<option value='all'>--Select--</option>");
            }

            if ($(this).is('#hardwareStandard')) {
                $("#technicalSpec").html("<option value='all'>--Select--</option>");
            }

            handleFilterChange();
        });

    });



    $(document).ready(function () {

        //Delete Post
        $('body').on('click', '#delete-asset', function () {

        var assetURL = $(this).data('url');
        var trObj = $(this);
        if(confirm("Are you sure, you want to delete this post?") == true){

                $.ajax({
                    url: assetURL,
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