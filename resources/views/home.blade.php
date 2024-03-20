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
    <div class="table-responsive ml-3 mr-3">
        <table class="table table-striped">
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
        </table>

<br>
<br>

        <table id="assets-table" class="table table-hover" width="1200px">
            <thead class="table-success">
                <tr>
                    <th>Sl no</th>
                    <th>Type</th>
                    <th>Hardware Standard</th>
                    <th>Technical Specification</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>

  </main>

<script type="text/javascript">
    
    $(document).ready(function() {
        $('#assets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('assets.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'type', name: 'type' },
                { data: 'hardware_standard', name: 'hardware_standard' },
                { data: 'date_published', name: 'datePublished' },
                { data: 'comments_count', name: 'commentsCount',
                    render: function(data, type, row) {
                        return '<a href="#" class="view-comments-page" data-post-id="' + row.id + '">' + data + '</a>';
                    }
                },
                { data: 'is_active', name: 'status' },
                {
                    data: 'id',
                    name: 'view',
                    render: function(data, type, row) {
                            return '<a href="{{ route("posts.show", ["post" => ":postId"]) }}" id="view-user" class="btn btn-primary"> View </a>'.replace(':postId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'edit',
                    render: function(data, type, row) {
                            return '<a href="{{ route("posts.edit", ["post" => ":postId"]) }}" id="edit-user" class="btn btn-secondary"> Edit </a>'.replace(':postId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'delete',
                    render: function(data, type, row) {
                        
                            return '<a href="javascript:void(0)" id="delete-post" data-url="{{ route("posts.destroy", ["post" => ":postId"]) }}" class="btn btn-danger"> Delete </a>'.replace(':postId', row.id);
                    }
                }
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html(index + 1);
            }
        });

        // $('#assets-table').on('click', '.view-comments-page', function(e) {
        //     e.preventDefault();
        //     var postId = $(this).data('post-id');

        //     // Comments view with postId
        //     loadComments(postId);
        // });


        function handleFilterChange() {
            var authorId = $('#authorName').val();
            var statusId = $('#postStatus').val();
            var commentsCount = $('#commentsCountPosts').val();

            var url = "{{ route('posts.index') }}?";

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

        function loadComments(postId) {
            $.ajax({
                url: '/posts/' + postId + '/comments',
                type: 'GET',
                success: function(response) {
                    var comments = response.comments;
                    var commentsHtml = '';
                    if (comments.length > 0) {
                        commentsHtml += '<ul>';
                        comments.forEach(function(comment) {
                            commentsHtml += '<li>' + comment.comment + '</li><br>';
                        });
                        commentsHtml += '</ul>';
                    } else {
                        commentsHtml = 'No comments available.';
                    }
                    $('#commentsContainer').html(commentsHtml);
                    $('#commentsModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

    });

</script>



@endsection