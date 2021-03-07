@extends('layouts.simple')
@section('pageTitle', 'Login')

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ mix('js/pages/op_auth_signin.js') }}"></script>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
        <!-- Header -->
        <div class="py-30 px-5 text-center">
            <h1 class="font-w700 display-4 mt-20 invisible" data-toggle="appear" data-timeout="50">
                <span class="font-size-h2 si si-energy text-success"></span>
                <span class="font-w300">harvester</span>
                <span class="font-w300 text-success">App</span>
            </h1>
            <h1 class="h2 font-w700 mt-50 mb-10">Welcome to Your Dashboard</h1>
            <h2 class="h4 font-w400 text-muted mb-0">Please sign in</h2>
        </div>
        <!-- END Header -->

        <!-- Sign In Form -->
        <div class="row justify-content-center px-5">
            <div class="col-sm-8 col-md-6 col-xl-4">
                <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js -->
                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                <form class="js-validation-signin" action="{{ route('login') }}" method="post">
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input type="text" class="form-control" id="login-username" name="login-username">
                                <label for="login-username">Username</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input type="password" class="form-control" id="login-password" name="login-password">
                                <label for="login-password">Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <div class="col-12">
                            <label class="css-control css-control-primary css-checkbox">
                                <input type="checkbox" class="css-control-input" id="signin-remember" name="signin-remember">
                                <span class="css-control-indicator"></span>
                                Remember me
                            </label>
                        </div>
                    </div>
                    <div class="form-group row gutters-tiny">
                        <div class="col-12 mb-10">
                            <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                                <i class="si si-login mr-10"></i> Sign In
                            </button>
                        </div>
                        <div class="col-sm-6 mb-5">
                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('register') }}">
                                <i class="fa fa-plus text-muted mr-5"></i> New Account
                            </a>
                        </div>
                        <div class="col-sm-6 mb-5">
                            <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('recover') }}">
                                <i class="fa fa-warning text-muted mr-5"></i> Forgot password
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Sign In Form -->
    </div>
    <!-- END Page Content -->
@endsection
