<!DOCTYPE html>
<html lang="en">
<head>
  <title>
    @yield('title')
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

  <!-- For data tables -->
  <!-- CSS -->
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .validation-error {
        color: red;
    }
    .no-data-message {
        color: rgb(187, 93, 93);
    }

    .max-tb-width{
      max-width: 790px;
    }

    .user-image {
      width: 550px;
      height: 250px;
    }
    
  </style>
  <script type="text/javascript">
  
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
  </script>

  @section('optional-section')  
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ route('assets.index'); }}">Home</a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{ route('assets.index'); }}">Assets Management</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="{{ route('asset.parameters'); }}">Assets Parameters</a>
        </li>
      </ul>
    </div>

    <div class="container mt-3 d-flex justify-content-end">
        <a href="{{ route('logout') }}" class="btn btn-info btn-lg mr-5">
          <span class="glyphicon glyphicon-log-out"></span> Log out
        </a>
    </div>
  </nav>
  <br>
  @show

</head>
<body>
    @yield('content')
</body>
</html>