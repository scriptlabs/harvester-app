@extends('layouts.simple')
@section('pageTitle', 'Error')

@section('content')
    <!-- Page Content -->
    <div class="hero bg-white">
        <div class="hero-inner">
            <div class="content content-full">
                <div class="py-30 text-center">
                    <div class="display-3 text-danger">
                        <i class="fa fa-history"></i> 419
                    </div>
                    <h1 class="h2 font-w700 mt-30 mb-10">Oops.. You just found an error page..</h1>
                    <h2 class="h3 font-w400 text-muted mb-50">We are sorry but the page you want to visit has expired..</h2>
                    <a class="btn btn-hero btn-rounded btn-alt-secondary" href="{{ route('dashboard') }}">
                        <i class="fa fa-arrow-left mr-10"></i> Back to dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
