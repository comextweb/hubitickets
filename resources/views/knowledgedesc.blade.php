@extends('layouts.auth')

@section('page-title')
    {{ __('Knowledge') }}
@endsection
@section('content')
    <div class="auth-wrapper auth-v1">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-dark default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ getFile(getSidebarLogo()) }}{{ '?' . time() }}" alt="logo" style="width:150px;" />
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('knowledge') }}"><i
                                        class="ti ti-arrow-circle-left"></i>{{ __('Back') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="row align-items-center justify-content-center text-start">
                <div class="col-xl-12 text-center">
                    <div class="mx-3 mx-md-5">
                    </div>
                    <div class="card">
                        <div class="card-body w-100">
                            <div class="">
                                <h4 class="text-primary mb-3">{{ $descriptions->title }}</h4>
                            </div>
                            <div class="text-start">
                                @if ($descriptions->count())
                                    <p class="mb-0">{!! $descriptions->description !!}</p>
                                @else
                                    <h6 class="card-title mb-0 text-center">{{ __('No Knowledges found.') }}</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection
