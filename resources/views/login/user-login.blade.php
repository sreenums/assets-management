@extends('layouts.layout')

@section('title', 'Login Home')

@section('optional-section')

@endsection

@section('content')

<br>
<style>
    a {
    text-decoration: none;
}
.login-page {
    width: 100%;
    height: 100vh;
    display: inline-block;
    display: flex;
    align-items: center;
}
.form-right i {
    font-size: 100px;
}
</style>

<div class="container">

    <div class="login-page bg-light">
        <div class="container">
            <div class="row">

                <div class="col-lg-10 offset-lg-1">
                @if(session('success'))
                <div class="alert alert-success ">
                    {{ session('success') }}
                </div>
                @endif
                  <h3 class="mb-3">Login Now</h3>
                    @if ($errors->has('loginError'))
                    <div class="validation-error">{{ $errors->first('loginError') }}</div>
                    @endif

                    <div class="bg-white shadow rounded">
                        <div class="row">
                            <div class="col-md-7 pe-0">
                                <div class="form-left h-100 py-5 px-5">
                                    <form action="/login" class="row g-4" id="userLogin" name="userLogin" method="POST">
                                        @csrf
                                            <div class="col-12">
                                                <label>Email Id<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                    <input type="email" class="form-control" name='email' id='email' placeholder="Enter valid Email Id" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label>Password<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary px-4 float-end mt-4" id="loginSubmit" name="loginSubmit">login</button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-5 ps-0 d-none d-md-block">
                                <div class="form-right h-100 bg-primary text-white text-center pt-5">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <h2 class="fs-1">Assets Management Login</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
  <!-- Section: Design Block -->
</div>

@endsection