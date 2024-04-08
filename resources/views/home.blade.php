@extends('layouts.layout')

@section('title', 'Assets Dashboard')

@section('content')

<br>

  <main role="main" class="container mt-5">

    <div class="starter-template mt-6 mb-4 d-flex justify-content-between align-items-center">
        <h1>Dashboard Home</h1>
        <div class="col-md-3">
            <div class="form-group ms-auto">
            <button id="export-button" class="btn btn-light"
                    data-export-route="{{ route('export.csv') }}" onclick="submitForm()">
                <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
            </button>
            </div>
        </div>
    </div>
    <form method="GET" name="searchForm" id="searchForm" action="{{ route('export.csv') }}" >
        <div class="container mt-5 d-flex justify-content-between align-items-center">
            <div class="text-right ">
                <a href="{{ route('assets.create'); }}" class="btn btn-outline-success">Add Asset</a>
            </div>

            <!--<div class="row align-items-center">List for
                <div class="col-md-3">
                    <select class="form-control" id="days" name="days">
                        <option value="">Day</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="months" name="months">
                        <option value="">Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="years" name="years">
                        <option value="">Year</option>
                    </select>
                </div>
            </div>-->
        </div>
        
        <div class="row mb-2 mt-4">
            <div class="col-md-4">
                <!-- Asset Type Filter -->
                <div class="form-group">
                    <label for="assetType">Type:</label>
                    <select id="assetType" name="assetType" class="form-control">
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
                <!-- Technical Specification Filter -->
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
                    <label for="status">Status:</label>
                    <select id="status" name="status" class="form-control">
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
            <div class="row align-items-center col-md-4">
                <label for="periodSearch">Age filter:</label>
                <div class="col-md-3">
                    <input type="text" id="periodSearch" name="periodSearch" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" placeholder="No" value="{{ request('periodSearch') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="periodFilter" name="periodFilter">
                        <option value="days">Days</option>
                        <option value="months">Months</option>
                        <option value="years">Years</option>
                    </select>
                </div>
                <!--<div class="col-md-3">
                    <select class="form-control" id="years" name="years">
                        <option value="">Year</option>
                    </select>
                </div>-->
            </div>
        </div>
    </form>
    <br>
    <table id="assets-table" class="table table-striped table-hover" >
        <thead class="table-success">
            
                <tr>
                    <th>Sl no</th>
                    <th><a href="{{ route('assets-type.index') }}">Type</a></th>
                    <th><a href="{{ route('hardware-standard.index') }}">Hardware Standard</a></th>
                    <th><a href="{{ route('technical-specs.index') }}">Technical Specification</a></th>
                    <th><a href="{{ route('users.index'); }}">User</a> / <a href="{{ route('locations.index'); }}">Location</a></th>
                    <th>Age</th>
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
    
  </main>

<script type="text/javascript">
    
    $(document).ready(function() {

        $(document).on('change','#assetType', function() {
        //$("#hardwareStandard").html("<option value='all'>--Select--</option>");
        //$("#technicalSpec").html("<option value='all'>--Select--</option>");
          let assetType = $(this).val();
          $.ajax({
              method: 'post',
              url: "{{ route('get.type.hardwares') }}",
              data: {
                assetType: assetType
              },
              success: function(res) {
                  if (res.status == 'success') {
                      let all_options = "<option value='all'>--Select--</option>";
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

      $(document).on('change','#hardwareStandard', function() {
          //$("#technicalSpec").html("<option value='all'>--Select--</option>");
          let hardwareStandard = $(this).val();
          
          $.ajax({
              method: 'post',
              url: "{{ route('get.hardware.technical.spec') }}",
              data: {
                hardwareStandard: hardwareStandard
              },
              success: function(res) {
                  if (res.status == 'success') {
                      let all_options = "<option value='all'>--Select--</option>";
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


    $(document).ready(function() {

        $('#assets-table').DataTable({
            columnDefs: [
                { targets: [1, 2, 3, 4, 5, 6, 7, 8, 9], orderable: false } // Disable sorting for all columns except Sl no (index 0)
            ],
            searching: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.asset') }}",
                // data: function (d) {
                //     d.days = $('#days').val(); // Pass the default age filtering value
                // }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'type', name: 'type' },
                { data: 'hardware_standard', name: 'hardware_standard' },
                { data: 'technicalSpecification', name: 'technical_specification_id' },
                { data: 'location', name: 'location_id' },
                { data: 'age', name: 'age' },
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
            var assetTypeId = $('#assetType').val();
            var hardwareStandard = $('#hardwareStandard').val();
            var technicalSpec = $('#technicalSpec').val();
            var statusId = $('#status').val();
            var assetSearch = $('#assetSearch').val();
            // var daySearch = $('#days').val();
            // var months = $('#months').val();
            // var years = $('#years').val();
            var periodSearch = $('#periodSearch').val();
            var periodFilter = $('#periodFilter').val();

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
            // if (daySearch) {
            //     url += "days=" + daySearch + "&";
            // }
            // if (months) {
            //     url += "months=" + months + "&";
            // }
            // if (years) {
            //     url += "years=" + years + "&";
            // }
            if (periodSearch) {
                url += "periodSearch=" + periodSearch + "&";
            }
            if (periodFilter) {
                url += "periodFilter=" + periodFilter + "&";
            }

            // Remove trailing '&' if exists
            url = url.replace(/&$/, "");

            $('#assets-table').DataTable().ajax.url(url).load();
        }

        //$('#assetSearch, #status, #assetType, #hardwareStandard, #technicalSpec, #days, #months, #years').on('keyup change', function() {
        $('#assetSearch, #status, #assetType, #hardwareStandard, #technicalSpec, #periodSearch, #periodFilter').on('keyup change', function() {
            
            // var daysValue = $('#days').val(); // Get the value of days dropdown
            // var monthsValue = $('#months').val(); // Get the value of months dropdown
            // var yearsValue = $('#years').val(); // Get the value of years dropdown
            // // Check if a value is present in days, months, and years
            // var isDateSpecified = daysValue || monthsValue || yearsValue;

            // if (!isDateSpecified) {
            //     alert("Please select a date filter! Default filter applied for 7 days.");
            //     $('#days').val(7);
            // }

            if ($(this).is('#assetType')) {
                $("#hardwareStandard").html("<option value='all'>--Select--</option>");
                $("#technicalSpec").html("<option value='all'>--Select--</option>");
            }

            if ($(this).is('#hardwareStandard')) {
                $("#technicalSpec").html("<option value='all'>--Select--</option>");
            }

            handleFilterChange();
        });

        $('#assets-table thead th').css('background-color', '#c2cbd9');

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

    // // Populate options for days
    // var daySelect = document.getElementById("days");
    // for (var i = 1; i <= 31; i++) {
    //     var option = document.createElement("option");
    //     option.text = i+" days";
    //     option.value = i;
    //         if (i == 7) {
    //             option.selected = true; // Set option with value 7 as selected
    //         }
    //     daySelect.add(option);
    // }

    // // Populate options for months
    // var monthSelect = document.getElementById("months");
    // for (var i = 1; i <= 12; i++) {
    //     var option = document.createElement("option");
    //     option.text = i + " months";
    //     option.value = i;
    //     monthSelect.add(option);
    // }

    // // Populate options for years
    // var yearSelect = document.getElementById("years");
    // for (var i = 1; i <= 5; i++) {
    //     var option = document.createElement("option");
    //     option.text = i+" years ";
    //     option.value = i;
    //     yearSelect.add(option);
    // }

    //Form submit for csv export
    function submitForm() {
        document.getElementById('searchForm').submit();
    }

</script>



@endsection