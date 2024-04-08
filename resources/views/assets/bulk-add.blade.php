@extends('layouts.layout')

@section('title', 'Bulk Upload')

@section('content')
<br>

<div class="container mt-5">
  <div class="mt-3">
    <a href="{{ route('assets.create') }}" class="btn btn-dark">Back</a>
  </div>
    <div class="row justify-content-center mb-5">
        <div class="col-md-6 mt-3">
          <h2>Assets Bulk Upload</h2>
            <div class="card">
                <div class="card-header">
                    File Upload
                </div>
                <div class="card-body">
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="fileInput">Choose File:</label>
                            <input type="file" class="form-control-file" id="fileInput" name="fileInput">
                        </div>
                        <button type="button" class="btn btn-primary mt-2" onclick='handleFile()'>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <!--<table class="table mt-3">
    <tr id="totalRecordsCnt">#</tr>
    <thead>
        <tr>
            <th>Asset Tag</th>
            <th>Serial No</th>
            <th>Type ID</th>
            <th>Hardware Standard ID</th>
            <th>Technical Specification ID</th>
            <th>Purchase Order</th>
            <th>Location ID</th>
            <th>Status</th>
            <th>Location</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>A032765</td>
            <td>SL676855</td>
            <td>2</td>
            <td>1</td>
            <td>3</td>
            <td>PO12335</td>
            <td>1</td>
            <td>1</td>
            <td>ABCD</td>
            <td><button type="button" class="btn-close" aria-label="Close"></button>
        </tr>
        
    </tbody>
  </table>-->
  <span id="totalRecordsCnt"></span>
  <div id="tableContainer"></div>
  <button type="button" class="btn btn-primary mt-2" id="uploadButton" name="uploadButton" style="display: none;">Upload</button>
</div>

<script type="text/javascript">

function handleFile() {
    var fileInput = document.getElementById('fileInput');
    var file = fileInput.files[0];
    var reader = new FileReader(); 

    reader.onload = function(e) {
        var fileContent = e.target.result; //console.log(fileContent); return;
        displayFileContent(fileContent);
    }

    reader.readAsText(file);
}

function displayFileContent(content) {
    var rows = content.trim().split('\n'); // Split content into rows
    var tableHtml = '<table class="table mt-3"><thead><tr>';

    // Assuming the first row contains column headers
    var headers = rows[0].split(',');

    // Generate table headers
    headers.forEach(function(header) {
        tableHtml += '<th>' + header + '</th>';
    });
    tableHtml += '<th>Remove</th>';

    tableHtml += '</tr></thead><tbody>';

    // Start from index 1 to skip the header row
    for (var i = 1; i < rows.length; i++) {
        var rowData = rows[i].split(',');

        tableHtml += '<tr>';

        rowData.forEach(function(data) {
            tableHtml += '<td>' + data + '</td>';
        });
        tableHtml += '<td><button type="button" class="btn-close" aria-label="Close" onclick="closeRow(this); removedRow(' + i + ');" ></button></td>';
        tableHtml += '</tr>';
    }

    tableHtml += '</tbody></table>';

    // Display the table on the page
    document.getElementById('tableContainer').innerHTML = tableHtml;
    document.getElementById('totalRecordsCnt').innerText = '# Records (' + (rows.length - 1) + ')';

    if(rows.length>0){
        document.getElementById('uploadButton').style.display = 'block';
    }

}

// Function to close row
function closeRow(button) {
    var row = button.closest('tr'); // Get closest table row
    row.remove(); // Remove the row from the table
}

function removedRow(row){
  
}
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

      $(document).on('change','#assetStatus', function() {
      let assetStatus = $(this).val();
      if (assetStatus == '2') {           //Assigned
            //$("#assetLocation").html("<option value=''>User Selected</option>");
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

                        $("#assetLocation").html(all_options);
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

                        $("#assetLocation").html(all_options);
                    }
                }
          });
        } 

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