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
        <!--<table class="table table-striped">
            <thead>
                <tr>
                    <th>Sl. No</th>
                    <th>Type</th>
                    <th>Hardware Standard</th>
                    <th>Technical Specification</th>
                    <th>Asset Tag</th>
                    <th>Status</th>
                    <th>PO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Workstation</td>
                    <td>LIGS</td>
                    <td>Hhx/8GB/1T</td>
                    <td>A344545</td>
                    <td>Assigned</td>
                    <td>PO12345</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Monitor</td>
                    <td>acer</td>
                    <td>23' HD</td>
                    <td>A344546</td>
                    <td>Assigned</td>
                    <td>PO12346</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Workstation</td>
                    <td>LIGS</td>
                    <td>Hhx/8GB/1T</td>
                    <td>A344547</td>
                    <td>Assigned</td>
                    <td>PO12347</td>
                </tr>
            </tbody>
        </table>-->

<br>
<br>

        <table id="assets-table" class="table table-striped table-hover" >
            <thead class="table-success">
                
                    <tr>
                        <th>Sl no</th>
                        <th>Type</th>
                        <th>Hardware Standard</th>
                        <th>Technical Specification</th>
                        <th>User/ Location</th>
                        <th>Status</th>
                        <th>Asset Tag</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tbody>
                    </tbody>
            </thead>
        </table>
        <br>
        <br>
        <br>
    </div>

  </main>

<script type="text/javascript">
    
    $(document).ready(function() {
        $('#assets-table').DataTable({
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

        // $('#assets-table').on('click', '.view-comments-page', function(e) {
        //     e.preventDefault();
        //     var assetId = $(this).data('asset-id');

        //     // Comments view with assetId
        //     loadComments(assetId);
        // });


        function handleFilterChange() {
            var authorId = $('#authorName').val();
            var statusId = $('#postStatus').val();
            var commentsCount = $('#commentsCountPosts').val();

            var url = "{{ route('assets.index') }}?";

            if (authorId) {
                url += "author=" + authorId + "&";
            }
            if (statusId) {
                url += "status=" + statusId + "&";
            }
            if (commentsCount) {
                url += "commentsCount=" + commentsCount + "&";
            }

            // Remove trailing '&' if exists
            url = url.replace(/&$/, "");

            $('#assets-table').DataTable().ajax.url(url).load();
        }

        // Event listeners for filter changes
        // $('#authorName, #postStatus, #commentsCountPosts').on('keyup change', function() {
        //     handleFilterChange();
        // });


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