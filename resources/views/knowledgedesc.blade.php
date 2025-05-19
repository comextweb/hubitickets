@extends('layouts.auth')

@section('page-title')
    {{ __('Knowledge') }}
@endsection
@section('content')
    <div class="auth-wrapper knowledge-sec">
        <div class="auth-content">
            <header class="custom-header">
                <a class="logo-col" href="{{ route('home') }}">
                    <img src="{{ getFile(getSidebarLogo()) }}{{ '?' . time() }}" alt="logo" />
                </a>
                <a href="{{ route('knowledge') }}"
                    class="btn btn-lg btn-primary back-btn text-white d-flex align-items-center justify-content-center"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Return">
                    <i class="ti ti-arrow-back-up"></i>
                </a>
            </header>

            <div class="row align-items-lg-start knowledge-des h-100 justify-content-center text-start">
                <div class="col-xl-5 col-lg-8 col-12 mx-auto text-center">
                    <div class="card">
                        <div class="card-body w-100">
                            <div class="">
                                <h4 class="mb-3">{{ $descriptions->title }}</h4>
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