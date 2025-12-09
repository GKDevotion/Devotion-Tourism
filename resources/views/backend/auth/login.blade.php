@extends('backend.auth.auth_master')

@section('auth_title')
    Login | Admin Panel
@endsection

@section('auth-content')
     <!-- login area start -->
     <div class="login-area">
        <div class="container">
            <div class="login-box">
                <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off">
                    @csrf
                    <div class="login-form-head">
                        <img src="{{url(getConfigurationfield('APP_LOGO'))}}" />
                    </div>
                    <div class="login-form-body">

                        @include('backend.layouts.partials.messages')

                        <h4 class="fs-4 card-title fw-bold mb-4">Log In</h4>
                        <div class="form-gp">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" id="exampleInputEmail1" name="email">
                            <i class="ti-user"></i>
                            <div class="text-danger"></div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" id="exampleInputPassword1" name="password">
                            <i class="ti-eye" style="cursor: pointer;" onmousedown="showPassword()" onmouseup="hidePassword()" onmouseleave="hidePassword()"></i>
                            <div class="text-danger"></div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row mb-4 rmber-area">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing" name="remember">
                                    <label class="custom-control-label" for="customControlAutosizing">Remember Me</label>
                                </div>
                            </div>
                            {{-- <div class="col-6 text-right">
                                <a href="#">Forgot Password?</a>
                            </div> --}}
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Log In <i class="ti-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->

    <script>
        function showPassword() {
          document.getElementById("exampleInputPassword1").type = "text";
        }

        function hidePassword() {
          document.getElementById("exampleInputPassword1").type = "password";
        }
    </script>
@endsection
