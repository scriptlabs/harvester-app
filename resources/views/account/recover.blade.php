@extends('layouts.simple')
@section('pageTitle', 'Recover')

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
            <h1 class="h2 font-w700 mt-50 mb-10">Don’t worry, we’ve got your back</h1>
            <h2 class="h4 font-w400 text-muted mb-0">Please enter your username or email</h2>
        </div>
        <!-- END Header -->

        <!-- Reminder Form -->
        <div class="row justify-content-center px-5">
            <div class="col-sm-8 col-md-6 col-xl-4">
                <!-- jQuery Validation functionality is initialized with .js-validation-reminder class in js/pages/op_auth_reminder.min.js which was auto compiled from _js/pages/op_auth_reminder.js -->
                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                <form class="js-validation-reminder" action="{{ route('recover') }}" method="post">
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input type="text" class="form-control" id="reminder-credential" name="reminder-credential">
                                <label for="reminder-credential">Username or Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                            <i class="fa fa-asterisk mr-10"></i> Password Reminder
                        </button>
                        <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ route('login') }}">
                            <i class="si si-login text-muted mr-10"></i> Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Reminder Form -->
    </div>
    <!-- END Page Content -->
@endsection
