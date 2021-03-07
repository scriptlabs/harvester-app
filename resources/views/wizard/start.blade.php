@extends('layouts.simple')
@section('pageTitle', 'Start')

@section('content')
    <!-- Hero -->
    <div class="bg-white bg-pattern" style="background-image: url('{{ asset('/media/various/bg-pattern-inverse.png') }}');">
        <div class="hero overflow-hidden">
            <div class="hero-inner">
                <div class="content content-full text-center">
                    <div class="pt-100 pb-100">
                        <h1 class="font-w700 display-4 mt-20 invisible" data-toggle="appear" data-timeout="50">
                            <span class="font-size-h2 si si-energy text-success"></span>
                            <span class="font-w300">harvester</span>
                            <span class="font-w300 text-success">App</span>
                        </h1>
                        <h2 class="h3 font-w400 text-muted mb-50 invisible" data-toggle="appear" data-class="animated fadeInDown" data-timeout="300">
                            START
                        </h2>
                        <div class="invisible" data-toggle="appear" data-class="animated fadeInUp" data-timeout="300">
                            <div class="terminal">
                                <div>{{ $gpio }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->
@endsection
