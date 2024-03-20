@extends('layouts.layout')

@section('title', 'Assets Parameters')

@section('content')

<br>

  <main role="main" class="container">

    <div class="text-right mt-5">
      <h3> Assets Parameters</h1>
    </div>
    <div class="table-responsive ml-3 mt-4">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td><a href="{{ route('type.home'); }}"> Asset Type </a></td>
                    <td>
                        <!--<a href=" {{ route('assets.type.add'); }} " id="view-user" class="btn btn-primary"> Add Type </a>-->
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td><a href="{{ route('hardware-standard.index'); }}">Hardware Standard</a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><a href="{{ route('technical-specs.index'); }}">Techspec</a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><a href="{{ route('locations.index'); }}">Location</a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><a href="{{ route('users.index'); }}">User</a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Assets</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

  </main>

@endsection