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
    </div>

  </main>





@endsection