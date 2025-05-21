@extends('layouts.auth')
@section('page-title')
    {{ __('FAQ') }}
@endsection
@section('content')
    <div class="auth-wrapper faq-sec">
        <div class="auth-content">
            {{-- Navbar --}}
            @include('layouts.navbar')
            <div class="faq-content-wrp">
                <h2 class="mb-4  f-w-600 text-center">{{ __('FAQ') }}</h2>
                @if ($faqs->count())
                    <div class="faq-list">
                        @foreach ($faqs as $faq)
                            <div class="faq-item">
                                <a href="javascript:;" class="faq-label d-flex align-items-center justify-content-between gap-3">
                                    <span> {{$faq->title}}</span>
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.8906 9.89062H14.1094V2.10938C14.1094 0.944391 13.165 0 12 0C10.835 0 9.89062 0.944391 9.89062 2.10938V9.89062H2.10938C0.944391 9.89062 0 10.835 0 12C0 13.165 0.944391 14.1094 2.10938 14.1094H9.89062V21.8906C9.89062 23.0556 10.835 24 12 24C13.165 24 14.1094 23.0556 14.1094 21.8906V14.1094H21.8906C23.0556 14.1094 24 13.165 24 12C24 10.835 23.0556 9.89062 21.8906 9.89062Z"
                                            fill="black" />
                                    </svg>

                                </a>
                                <div class="faq-content">
                                    <p class="mb-0">{{$faq->description}} </p>
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
            @include('layouts.footer')
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const faqLabels = document.querySelectorAll('.faq-label');

            faqLabels.forEach(label => {
                label.addEventListener('click', () => {
                    const content = label.nextElementSibling;
                    const isActive = label.classList.contains('active');

                    // Close all
                    faqLabels.forEach(item => {
                        item.classList.remove('active');
                        item.nextElementSibling.classList.remove('show');
                    });

                    // Toggle current if it wasn't already active
                    if (!isActive) {
                        label.classList.add('active');
                        content.classList.add('show');
                    }
                });
            });

            // Open first item by default
            if (faqLabels.length > 0) {
                faqLabels[0].classList.add('active');
                faqLabels[0].nextElementSibling.classList.add('show');
            }
        });
    </script>
@endpush