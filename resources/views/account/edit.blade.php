@extends('layouts.backend')
@section('pageTitle', 'Account')

@section('content')
    <!-- Page Content -->
    <?php /*
    <!-- User Info -->
    <div class="bg-image bg-image-bottom" style="background-image: url({{ asset('media/photos/photo13@2x.jpg') }});">
        <div class="bg-black-op-75 py-30">
            <div class="content content-full text-center">
                <!-- Avatar -->
                <div class="mb-15">
                    <a class="img-link" href="/">
                        <img class="img-avatar img-avatar96 img-avatar-thumb" src="assets/media/avatars/avatar15.jpg" alt="">
                    </a>
                </div>
                <!-- END Avatar -->

                <!-- Personal -->
                <h1 class="h3 text-white font-w700 mb-10">John Smith</h1>
                <h2 class="h5 text-white-op">
                    Product Manager <a class="text-primary-light" href="javascript:void(0)">@GraphicXspace</a>
                </h2>
                <!-- END Personal -->

                <!-- Actions -->
                <a href="be_pages_generic_profile.html" class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5">
                    <i class="fa fa-arrow-left mr-5"></i> Back to Profile
                </a>
                <!-- END Actions -->
            </div>
        </div>
    </div>
    <!-- END User Info -->
    */ ?>
    <!-- Main Content -->
    <div class="content">
        <!-- User Profile -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-user-circle mr-5 text-muted"></i> User Profile
                </h3>
            </div>
            <div class="block-content">
                <form action="be_pages_generic_profile.edit.html" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Your account’s vital info. Your username will be publicly visible.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-username">Username</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-username" name="profile-settings-username" placeholder="Enter your username.." value="john.doe">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-name">Name</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-name" name="profile-settings-name" placeholder="Enter your name.." value="John Doe">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-email">Email Address</label>
                                    <input type="email" class="form-control form-control-lg" id="profile-settings-email" name="profile-settings-email" placeholder="Enter your email.." value="john.doe@example.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-10 col-xl-6">
                                    <div class="push">
                                        <img class="img-avatar" src="{{ asset('media/avatars/avatar15.jpg') }}" alt="">
                                    </div>
                                    <div class="custom-file">
                                        <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                                        <input type="file" class="custom-file-input" id="profile-settings-avatar" name="profile-settings-avatar" data-toggle="custom-file-input">
                                        <label class="custom-file-label" for="profile-settings-avatar">Choose new avatar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-alt-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END User Profile -->

        <!-- Change Password -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-asterisk mr-5 text-muted"></i> Change Password
                </h3>
            </div>
            <div class="block-content">
                <form action="be_pages_generic_profile.edit.html" method="post" onsubmit="return false;">
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Changing your sign in password is an easy way to keep your account secure.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-password">Current Password</label>
                                    <input type="password" class="form-control form-control-lg" id="profile-settings-password" name="profile-settings-password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-password-new">New Password</label>
                                    <input type="password" class="form-control form-control-lg" id="profile-settings-password-new" name="profile-settings-password-new">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-password-new-confirm">Confirm New Password</label>
                                    <input type="password" class="form-control form-control-lg" id="profile-settings-password-new-confirm" name="profile-settings-password-new-confirm">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-alt-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Change Password -->

        <!-- Billing Information -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-gears mr-5 text-muted"></i> Settings
                </h3>
            </div>
            <div class="block-content">
                <form action="be_pages_generic_profile.edit.html" method="post" onsubmit="return false;">
                    <div class="row items-push">
                        <div class="col-lg-3">
                            <p class="text-muted">
                                Your billing information is never shown to other users and only used for creating your invoices.
                            </p>
                        </div>
                        <div class="col-lg-7 offset-lg-1">
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-company">Company Name (Optional)</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-company" name="profile-settings-company">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="profile-settings-firstname">Firstname</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-firstname" name="profile-settings-firstname">
                                </div>
                                <div class="col-6">
                                    <label for="profile-settings-lastname">Lastname</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-lastname" name="profile-settings-lastname">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-street-1">Street Address 1</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-street-1" name="profile-settings-street-1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-street-2">Street Address 2</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-street-2" name="profile-settings-street-2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="profile-settings-city">City</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-city" name="profile-settings-city">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="profile-settings-postal">Postal code</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-postal" name="profile-settings-postal">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="profile-settings-vat">VAT Number</label>
                                    <input type="text" class="form-control form-control-lg" id="profile-settings-vat" name="profile-settings-vat" value="IA00000000" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-alt-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Billing Information -->
    </div>
    <!-- END Main Content -->
    <!-- END Page Content -->
@endsection
