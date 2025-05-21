@extends('layouts.admin')
@section('page-title')
    {{ __('Notification Templates') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Notification Templates') }}</li>
@endsection
@section('action-button')
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="{{ route('notification-templates.index') }}" class="btn btn-sm btn-primary"
                data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Return"><i
                    class="ti ti-arrow-back-up"></i>
            </a>
        </div>
    </div>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('content')
    @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
        <div class="text-end mb-2">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['notification-templates']) }}" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot me-1"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif
    <div class="pt-md-4">
        <div class="row">
            <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-5 col-12">
                <div class="card manage-notification-left-col sticky-top mb-0">
                    <div class="card-header card-body">
                        <h5 class="font-weight-bold">{{ __('Variables') }}</h5>
                    </div>
                    <div class="text-xs p-3">
                        @foreach ($templateVariables as $key => $var)
                            <p class="mb-2">{{ __($key) }} : <span class="pull-right text-primary">{{ '{' . $var . '}' }}</span></p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-7 col-12">
                <div class="manage-notification-right-col">
                    <div class="card language-sidebar p-2">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @foreach ($languages as $key => $lang)
                                <a class="list-group-item list-group-item-action border-0 rounded-1 {{ $curr_noti_tempLang->lang == $key ? 'active' : '' }}"
                                    href="{{ route('manage.notification.language', [$notification_template->id, $key]) }}">
                                    {{ Str::ucfirst($lang) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card p-3 border">
                        <form action="{{ route('notification-templates.update', $curr_noti_tempLang->parent_id) }}" class="needs-validation mt-3" method="POST" enctype="multipart/form-data"
                        novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="name" class="col-form-label text-dark">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control font-style" value="{{ $notification_template->action }}" disabled>
                            </div>
                            <div class="form-group col-12 mb-0">
                                <label for="content" class="col-form-label text-dark">{{ __('Notification Message') }}</label>
                                <textarea name="content" class="summernote-simple form-control font-style" required>{{ $curr_noti_tempLang->content }}</textarea>
                                <p class="text-danger summernote_text"></p>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="lang" value="{{ $curr_noti_tempLang->lang }}">
                                <input type="submit" value="{{ __('Save') }}"
                                    class="btn btn-print-invoice  btn-primary">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
