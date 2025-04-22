@extends('layouts.auth')
@section('page-title')
    {{ __('FAQ') }}
@endsection
@section('content')
    <div class="auth-wrapper auth-v1">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            {{-- Navbar --}}
            @include('layouts.navbar')

            <div class="row align-items-center justify-content-center text-start">
                <div class="col-xl-12 text-center">
                    <div class="mx-3 mx-md-5">
                        <h2 class="mb-3 text-primary f-w-600">{{ __('FAQ') }}</h2>
                    </div>

                    <div class="text-start faq">
                        @if ($faqs->count())
                            <div class="accordion accordion-flush" id="faq-accordion">
                                @foreach ($faqs as $index => $faq)
                                    <div class="accordion-item card">
                                        <h2 class="accordion-header" id="heading-{{ $index }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $index }}">
                                                <span class="d-flex align-items-center">
                                                    <i class="ti ti-info-circle text-primary"></i> {{ $faq->title }}
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $index }}"
                                            class="accordion-collapse collapse @if ($index == 0) show @endif" aria-labelledby="heading-{{ $index }}" data-bs-parent="#faq-accordion">
                                            <div class="accordion-body">
                                                <p class="mb-0">{!! $faq->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 text-center">{{ __('No Faqs found.') }}</h6>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
@endsection
