@extends('layouts.layout')

@section('title', 'Bulk Upload')

@section('content')
<br>
<style>
    .error-row {
        background-color: #ffe6e6; /* Light red background */
        color: #ff0000; /* Red text color */
    }
</style>
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
                    <form method="POST" action="{{ route('upload.asset.csv') }}" id="fileUploadForm" name="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                            <label for="importCsv">Choose File:</label>
                            <input type="file" class="form-control-file" id="importCsv" name="importCsv">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2" onclick='appendFile();'>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <span id="totalRecordsCnt"></span>
  <div id="tableContainer"></div>
  <div id="uploadConfirm" name="uploadConfirm" style="display: none;" >
  <div class="mt-3"><b>Validation Failed (Will not be updated)</b></div>
  <table class="table mt-3" id="failedRecordsTable"></table>
  <div class="mt-3"><b>Validation Success</b></div>
  <table class="table mt-3" id="successfulRecordsTable"></table>
  <form action="{{ route('save.upload.csv') }}" id="fileSaveForm" name="fileSaveForm">
    <button type="submit" class="btn btn-primary mt-2" id="uploadButton" name="uploadButton" >Upload</button>
  </form>
  </div>
</div>

<script type="text/javascript">

function appendFile() {
    errorRows = [];
    // Get the file input element from the source form
    var fileInput = document.getElementById('importCsv');

    // Get the destination form
    var destinationForm = document.getElementById('fileSaveForm');

    // Clone the file input element
    var clonedFileInput = fileInput.cloneNode(true);
    clonedFileInput.style.display = 'none';

    // Append the cloned file input to the destination form
    destinationForm.appendChild(clonedFileInput);

}

// Function to close row
function closeRow(button) {
    var row = button.closest('tr'); // Get closest table row
    row.remove(); // Remove the row from the table
}

let removedRows = [];
function removedRow(row){
    
    if (!errorRows.includes(row)) {
                    errorRows.push(row);
                    console.log(errorRows);
    }
    let totalCountText = document.getElementById('totalRecordsCnt').innerText;

    // Extract the count from the text content
    let totalCount = parseInt(totalCountText.match(/\d+/)[0]) - 1;

    if(totalCount <= 0){
        document.getElementById('uploadButton').disabled = 'true';
    }
    document.getElementById('totalRecordsCnt').innerText = '# Records (' + (totalCount) + ')';
}

$(document).ready(function() {
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
            if(response.fileTypeError){
                alert(response.errorMessage);
                $('#uploadButton').prop('disabled', true);
                $('#fileUploadForm')[0].reset(); 
                $('#fileSaveForm')[0].reset();
                $('#uploadConfirm').empty();
                $('#totalRecordsCnt').empty();
                $('#uploadConfirm').hide();
                return;
            }else{
                // Process the response and display data in table
                displayData(response);
            }

        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });

    });

});

$(document).ready(function() {
    //Upload Save
    $('#fileSaveForm').submit(function(e) {

        let totalCountText = document.getElementById('totalRecordsCnt').innerText;
        // Extract the count from the text content
        let totalCount = parseInt(totalCountText.match(/\d+/)[0]);
        if(totalCount <= 0){
            alert('No Records to Upload!'); return false;
        }
    
      e.preventDefault();
        // Create a new FormData object
        var formData = new FormData(this);

        // Append the errorRows array to the FormData object
        formData.append('errorRows', JSON.stringify(errorRows));
      
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message);
            $('#uploadButton').prop('disabled', true);
            // $('#fileUploadForm')[0].reset(); 
            // $('#fileSaveForm')[0].reset();
            // $('#uploadConfirm').empty();
            // $('#totalRecordsCnt').empty();
            // $('#uploadConfirm').hide();
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });

    });

});

function displayData(data) {
    var failedRecords = data.failedRecords;

    var successfulRecords = data.successfulRecords;
    if(successfulRecords.rows.length > 0){
        document.getElementById('uploadButton').style.display = 'block';
        document.getElementById('totalRecordsCnt').innerText = '# Records (' + (successfulRecords.rows.length) + ')';
    }else{
        document.getElementById('uploadButton').disabled = 'true';
    }
    
    // Display failed records
    displayRecords(failedRecords, '#failedRecordsTable');
    
    // Display successful records
    displayRecords(successfulRecords, '#successfulRecordsTable');

    document.getElementById('uploadConfirm').style.display = 'block';
}

let errorRows = [];
function displayRecords(records, tableId) {
    var table = $(tableId);
    table.empty();
    
    // Create table header
    var headerRow = $('<tr>');
    records.header.forEach(function(header) {
        headerRow.append($('<th>').text(header));
    });
    headerRow.append($('<th>').text("Remove"));
    table.append(headerRow);
    
    
    // Create table rows
    records.rows.forEach(function(rowData) {
        var hasError = '';
        var row = $('<tr>');
        records.header.forEach(function(header) {
            row.append($('<td>').text(rowData.data[header]));
            var errorMessages = []; // Array to store error messages

            // Check if there are validation errors for the current row
            if (rowData.errors && rowData.errors.length > 0) {
                hasError = true; // Set the flag to true if errors exist
                errorMessages = rowData.errors; // Store error messages
                if (!errorRows.includes(rowData.row)) {
                    errorRows.push(rowData.row);
                }
            }

            // Add a CSS class to the row based on the presence of errors
            if (hasError) {
                row.addClass('error-row');

                // Hover event handler to display error messages on row hover
                row.hover(
                    function() { // Mouse enter
                        var errorText = errorMessages.join(', '); // Concatenate error messages
                        $(this).attr('title', errorText); // Set error messages as title attribute
                    },
                    function() { // Mouse leave
                        $(this).removeAttr('title'); // Remove title attribute
                    }
                );
            }


        });
        

        // Append the close button to the row
        var removeButton = $('<button>', {
            type: 'button',
            class: 'btn-close',
            'aria-label': 'Close',
            click: function() {
                closeRow(this);
                removedRow(rowData.row); // Assuming i is defined somewhere in your code
            }
        });
        var removeButtonCell = $('<td>').append(removeButton);
        row.append(removeButtonCell);

        table.append(row);
    });

    console.log(errorRows);

}

</script>



@endsection