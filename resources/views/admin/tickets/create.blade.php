@extends('layouts.admin')
@stack('whatsappchatbot')
@section('page-title')
    {{ __('Create Ticket') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Ticket') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <form action="{{ route('admin.tickets.store') }}" class="needs-validation mt-3" method="POST" enctype="multipart/form-data"
        novalidate>
        @csrf
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div
                        class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                        <h5>{{ __('Ticket Information') }}</h5>
                        @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                            <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                data-ajax-popup-over="true" data-url="{{ route('generate', ['support']) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                                    </i>{{ __('Generate with AI') }}</a>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">

                            @if (!$customFields->isEmpty())
                                @include('admin.customFields.formBuilder')
                            @endif
                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <button class="btn btn-secondary custom-cancel-btn btn-submit me-2" type="button"
                                onclick="window.location='{{ route('admin.new.chat') }}'">{{ __('Cancel') }}</button>
                            <button id="submitBtn" class="btn btn-primary btn-block btn-submit">{{ __('Create') }}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                if (form.checkValidity()) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Sending...") }}';
                } else {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
            
            // Opcional: Rehabilitar si hay error en la petición
            window.addEventListener('unload', function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __("Create") }}';
            });
        });
    </script>

@endpush
